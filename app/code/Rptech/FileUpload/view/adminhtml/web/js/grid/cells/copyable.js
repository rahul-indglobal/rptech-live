// File: app/code/Your/Module/view/adminhtml/web/js/grid/cells/copyable.js
define([
    'Magento_Ui/js/grid/cells/html',
    'mage/translate',
    'jquery'
], function (Html, $t, $) {
    'use strict';

    return Html.extend({
        defaults: {
            copyableValue: ''
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();
            this.copyableValue = this.source.get('data.link');
            return this;
        },

        /**
         * Get the copyable value for the cell
         * @returns {string}
         */
        getCopyableValue: function () {
            return this.copyableValue;
        },

        /**
         * Handle the click event on the cell
         */
        handleClick: function () {
            var textToCopy = this.getCopyableValue();
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(textToCopy).select();
            document.execCommand('copy');
            tempInput.remove();

            // Optionally, you can display a success message
            alert($t('Copied to clipboard: ') + textToCopy);
        },

        /**
         * @inheritdoc
         */
        getValue: function (row) {
            console.log('i m here line 50')
            return this._super(row) + '<span data-bind="click: handleClick, style: { cursor: \'pointer\' }, attr: { title: \'' + $t('Click to copy') + '\' }">&#128203;</span>';
        }
    });
});
