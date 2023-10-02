pimcore.registerNS("pimcore.object.classes.data.markdown");
pimcore.object.classes.data.markdown = Class.create(pimcore.object.classes.data.data, {
    type: "markdown",

    /**
     * Define where this data type is allowed
     */
    allowIn: {
        object: true,
        objectbrick: true,
        fieldcollection: true,
        localizedfield: true,
        classificationstore : true,
        block: true,
        encryptedField: true
    },

    initialize: function (treeNode, initData) {
        this.type = "markdown";

        this.initData(initData);

        // overwrite default settings
        this.availableSettingsFields = ["name","title","tooltip","mandatory","noteditable","invisible",
            "visibleGridView","visibleSearch","style"];

        this.treeNode = treeNode;
    },

    getTypeName: function () {
        return t("Markdown");
    },

    getGroup: function () {
        return "text";
    },


    getIconClass: function () {
        return "pimcore_icon_document";
    },

    getLayout: function ($super) {

        $super();

        this.specificPanel.removeAll();
        var specificItems = this.getSpecificPanelItems(this.datax);
        this.specificPanel.add(specificItems);

        return this.layout;
    },

    getSpecificPanelItems: function (datax, inEncryptedField) {
        const stylingItems = [
            {
                xtype: "textfield",
                fieldLabel: t("width"),
                name: "width",
                value: datax.width
            },
            {
                xtype: "displayfield",
                hideLabel: true,
                value: t('width_explanation')
            },
            {
                xtype: "textfield",
                fieldLabel: t("height"),
                name: "height",
                value: datax.height
            },
            {
                xtype: "displayfield",
                hideLabel: true,
                value: t('height_explanation')
            },
            {
                xtype: "checkbox",
                fieldLabel: t("show_charcount"),
                name: "showCharCount",
                value: datax.showCharCount
            }
        ];

        if (this.isInCustomLayoutEditor()) {
            return stylingItems;
        }

        return stylingItems.concat([
            {
                xtype: "numberfield",
                fieldLabel: t("max_length"),
                name: "maxLength",
                value: datax.maxLength
            },
            {
                xtype: "checkbox",
                fieldLabel: t("exclude_from_search_index"),
                name: "excludeFromSearchIndex",
                checked: datax.excludeFromSearchIndex
            }
        ]);
    },

    applySpecialData: function(source) {
        if (source.datax) {
            if (!this.datax) {
                this.datax =  {};
            }
            Ext.apply(this.datax,
                {
                    width: source.datax.width,
                    height: source.datax.height,
                    maxLength: source.datax.maxLength,
                    showCharCount: source.datax.showCharCount,
                    excludeFromSearchIndex: source.datax.excludeFromSearchIndex
                });
        }
    }
});