/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Ui/js/form/element/ui-select'
], function ($, Select) {
    'use strict';

    return Select.extend({

        getValuesSelected: function ()
        {
            var value = this.value();
            return value;
        },
        /**
         * Get selected element labels
         *
         * @returns {Array} array labels
         */
        getSelected: function () {
            var selected = this.value();
            console.log(selected);
            return this.cacheOptions.plain.filter(function (opt) {
                return _.isArray(selected) ?
                    _.contains(selected, opt.value) :
                    selected == opt.value;//eslint-disable-line eqeqeq
            });
        },
        /**
         * Parse data and set it to options.
         *
         * @param {Object} data - Response data object.
         * @returns {Object}
         */
        setParsed: function (data) {
            var option = this.parseData(data);

            if (data.error) {
                return this;
            }

            this.options([]);
            this.setOption(option);
            this.set('newOption', option);
        },

        /**
         * Normalize option object.
         *
         * @param {Object} data - Option object.
         * @returns {Object}
         */
        parseData: function (data) {
            alert('bbbb');
            return {
                'is_active': data.category['is_active'],
                level: data.category.level,
                value: data.category['entity_id'],
                label: data.category.name,
                parent: data.category.parent
            };
        }

    });
});
