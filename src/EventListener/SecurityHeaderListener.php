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

namespace App\EventListener;

use Exception;
use LogicException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * Class SecurityHeaderListener
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-10-24)
 * @since 0.1.0 (2023-10-24) First version.
 */
readonly class SecurityHeaderListener
{
    private string $nonceScript;

    private string $nonceStyle;

    /**
     * @throws Exception
     */
    public function __construct(private ParameterBagInterface $parameterBag)
    {
        $this->nonceScript = $this->getNonce();
        $this->nonceStyle = $this->getNonce();
    }

    /**
     * Function onKernelResponse.
     *
     * @param ResponseEvent $event
     * @return void
     * @throws Exception
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            /* Don't do anything if it's not the master request. */
            return;
        }

        $request = $event->getRequest();

        $path = $request->getPathInfo();

        if (str_starts_with($path, '/admin')) {
            return;
        }

        if (str_starts_with($path, '/_wdt')) {
            return;
        }

        if (str_starts_with($path, '/js')) {
            return;
        }

        $response = $event->getResponse();

        /* Set nonce strings to web developer toolbar in Symfony. */
        $response->headers->set('X-SymfonyProfiler-Script-Nonce', $this->nonceScript);
        $response->headers->set('X-SymfonyProfiler-Style-Nonce', $this->nonceStyle);

        $this->addContentSecurityPolicy($event);
        $this->addReferrerPolicy($event);
        $this->addStrictTransportSecurity($event);
        $this->addXContentTypeOptions($event);
        $this->addXFrameOptions($event);
        $this->addPermissionsPolicy($event);

        $this->addMatomoScript($event);
    }

    /**
     * Adds the Content-Security-Policy header to the response.
     *
     * @param ResponseEvent $event
     * @return void
     */
    private function addContentSecurityPolicy(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $scriptSrc = $this->parameterBag->get('security_header.csp.script_src');
        $styleSrc = $this->parameterBag->get('security_header.csp.style_src');
        $imgSrc = $this->parameterBag->get('security_header.csp.img_src');
        $fontSrc = $this->parameterBag->get('security_header.csp.font_src');
        $connectSrc = $this->parameterBag->get('security_header.csp.connect_src');

        if (!is_string($scriptSrc) || !is_string($styleSrc) || !is_string($imgSrc) ||!is_string($fontSrc) ||!is_string($connectSrc)) {
            throw new LogicException('The security csp header was given in wrong format.');
        }

        $environment = $event->getRequest()->server->get('APP_ENV');

        if ($environment !== 'dev') {
            $scriptSrc .= sprintf(' \'nonce-%s\'', $this->nonceScript);
        }

        $response->headers->set('Content-Security-Policy', sprintf(
            'script-src %s; style-src %s; img-src %s; font-src %s; connect-src %s',
            $scriptSrc,
            $styleSrc,
            $imgSrc,
            $fontSrc,
            $connectSrc
        ));
    }

    /**
     * Adds the Referrer-Policy header to the response.
     *
     * @param ResponseEvent $event
     * @return void
     */
    private function addReferrerPolicy(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $referrerPolicy = $this->parameterBag->get('security_header.referrer_policy');

        if (!is_string($referrerPolicy)) {
            throw new LogicException('The referrer policy was given in wrong format.');
        }

        $response->headers->set('Referrer-Policy', $referrerPolicy);
    }

    /**
     * Adds the Strict-Transport-Security header to the response.
     *
     * @param ResponseEvent $event
     * @return void
     */
    private function addStrictTransportSecurity(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $strictTransportSecurity = $this->parameterBag->get('security_header.strict_transport_security');

        if (!is_string($strictTransportSecurity)) {
            throw new LogicException('The strict transport security was given in wrong format.');
        }

        $response->headers->set('Strict-Transport-Security', sprintf('max-age=%s', $strictTransportSecurity));
    }

    /**
     * Adds the X-Content-Type-Options header to the response.
     *
     * @param ResponseEvent $event
     * @return void
     */
    private function addXContentTypeOptions(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $xContentTypeOptions = $this->parameterBag->get('security_header.x_content_type_options');

        if (!is_bool($xContentTypeOptions)) {
            throw new LogicException('The security header was given in wrong format.');
        }

        if (!$xContentTypeOptions) {
            return;
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Adds the X-Frame-Options header to the response.
     *
     * @param ResponseEvent $event
     * @return void
     */
    private function addXFrameOptions(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $xFrameOptions = $this->parameterBag->get('security_header.x_frame_options');

        if (!is_string($xFrameOptions)) {
            throw new LogicException('The x-frame-options header was given in wrong format.');
        }

        $response->headers->set('X-Frame-Options', $xFrameOptions);
    }

    /**
     * Adds the Permissions-Policy header to the response.
     *
     * @param ResponseEvent $event
     * @return void
     */
    private function addPermissionsPolicy(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $permissionsPolicy = $this->parameterBag->get('security_header.permissions_policy');

        if (!is_string($permissionsPolicy)) {
            throw new LogicException('The permissions policy header was given in wrong format.');
        }

        $response->headers->set('Permissions-Policy', $permissionsPolicy);
    }

    /**
     * Adds the Matomo script
     *
     * @param ResponseEvent $event
     * @return void
     */
    private function addMatomoScript(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $htmlContent = $response->getContent();

        if (!is_string($htmlContent)) {
            return;
        }

        $position = strpos($htmlContent, '</head>');

        if (false === $position) {
            return;
        }

        $matomoCode = sprintf($this->getMatomoCode(), $this->nonceScript);

        /* Add Matomo Code */
        $htmlContent = substr_replace($htmlContent, $matomoCode, $position, 0);

        $response->setContent($htmlContent);
        $event->setResponse($response);
    }

    /**
     * @return string
     */
    private function getMatomoCode(): string
    {
        return <<<MATOME_CODE

<!-- Matomo -->
<script type="text/javascript" nonce="%s">
    var _paq = window._paq = window._paq || [];
    /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
        var u="//matomo.ixno.de/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '1']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
    })();
</script>
<!-- End Matomo Code -->

MATOME_CODE;
    }

    /**
     * Return a random nonce.
     *
     * @return string
     * @throws Exception
     */
    private function getNonce(): string
    {
        $hash = base64_encode(hash('sha256', (string) random_int(1_000_000_000, 9_999_999_999), true));

        return sprintf('sha256-%s', $hash);
    }
}