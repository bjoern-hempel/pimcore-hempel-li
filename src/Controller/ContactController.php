<?php

/*
 * This file is part of the https://github.com/bjoern-hempel/pimcore-hempel-li.git project.
 *
 * (c) 2023 Björn Hempel <bjoern@hempel.lil>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\DataMapper\News\NewsLatestDataMapper;
use App\Form\ContactFormType;
use LogicException;
use Pimcore\Controller\FrontendController;
use Pimcore\Mail;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

/**
 * Class ContactController
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class ContactController extends FrontendController
{
    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(protected TranslatorInterface $translator)
    {
    }

    /**
     * Renders the /contact page.
     *
     * @param Request $request
     * @return Response
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function indexAction(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);

        /* The form is not submitted */
        if (!$form->isSubmitted()) {
            return $this->render('contact/index.html.twig', [
                'locale' => $request->getLocale(),
                'form' => $form->createView(),
                'latestBlogs' => NewsLatestDataMapper::getLast($request),
            ]);
        }

        /* The form is not valid */
        if (!$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                if (!$error instanceof FormError) {
                    continue;
                }

                $cause = $error->getCause();

                if (!$cause instanceof ConstraintViolation) {
                    $this->addFlash('error', sprintf('%s', $error->getMessage()));
                    continue;
                }

                $fieldNames = sscanf($cause->getPropertyPath(), "children[%[^]]]");

                if (!is_array($fieldNames)) {
                    throw new LogicException(sprintf('Invalid property path "%s"', $cause->getPropertyPath()));
                }

                $fieldName = $this->translator->trans(sprintf('placeholder.%s', $fieldNames[0]));

                $this->addFlash('error', sprintf('%s: %s', $fieldName, $error->getMessage()));
            }

            return $this->render('contact/index.html.twig', [
                'locale' => $request->getLocale(),
                'form' => $form->createView(),
                'latestBlogs' => NewsLatestDataMapper::getLast($request),
            ]);
        }

        /* Start sending email */
        $formData = $form->getData();

        if (!is_array($formData)) {
            throw new LogicException('The returned data must be an array.');
        }

        if (!array_key_exists('email', $formData)) {
            throw new LogicException('The "email" field is required.');
        }

        $from = $formData['email'];
        $to = 'bjoern@hempel.li';

        try {
            $mail = new Mail();
            $mail->from($from);
            $mail->to($to);
            $mail->setDocument('/emails/contact-us');
            $mail->setParams($formData);
            $mail->send();
        } catch (Throwable $e) {
            $this->addFlash('error', sprintf('An error occurred while sending the email to "%s": "%s"', $to, $e->getMessage()));
            return $this->redirect($this->generateUrl('contact'));
        }

        $this->addFlash('success', sprintf('Mail was sent successfully to "%s".', $to));
        return $this->redirect($this->generateUrl('contact'));
    }

    /**
     * Renders the email content for the Pimcore administration.
     *
     * @param Request $request
     * @return Response
     */
    public function contactMailAction(Request $request): Response
    {
        $attributes = $request->attributes->all();

        return $this->render('emails/contact.html.twig', $attributes);
    }
}
