/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'jquery',
    'mageUtils',
    'uiElement',
    'Vkr_Kalman/js/product/storage/storage-service',
    'Magento_Customer/js/customer-data',
    'Magento_Catalog/js/product/view/product-ids-resolver'
], function (_, $, utils, Element, storage, customerData, productResolver) {
    'use strict';

    return Element.extend({
        defaults: {
            identifiersConfig: {
                namespace: 'kalman_recently_viewed_product'
            },
            productStorageConfig: {
                namespace: 'kalman_recently_viewed_product',
                customerDataProvider: 'kalman_recently_viewed_product',
                updateRequestConfig: {
                    url: '',
                    method: 'GET',
                    dataType: 'json'
                },
                className: 'DataStorage'
            },
            ids: {},
            listens: {
                ids: 'idsHandler'
            }
        },

        /**
         * Initializes provider component.
         *
         * @returns {Provider} Chainable.
         */
        initialize: function () {
            this._super()
                .initIdsStorage();

            return this;
        },

        /**
         * Calls 'initObservable' of parent
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            this._super();
            this.observe('ids');

            return this;
        },

        /**
         * Initializes ids storage.
         *
         * @returns {Provider} Chainable.
         */
        initIdsStorage: function () {
            storage.onStorageInit(this.identifiersConfig.namespace, this.idsStorageHandler.bind(this));

            return this;
        },

        /**
         * Initializes ids storage handler.
         *
         * @param {Object} idsStorage
         */
        idsStorageHandler: function (idsStorage) {
            this.idsStorage = idsStorage;
            this.productStorage = storage.createStorage(this.productStorageConfig);
            this.productStorage.data.subscribe(this.dataCollectionHandler.bind(this));


            this._resolveDataByIds();

        },

        /**
         * Callback, which load by ids from ids-storage product data
         *
         * @private
         */
        _resolveDataByIds: function () {
            this.initIdsListener();
            this.idsMerger(
                this.idsStorage.get(),
                this.prepareDataFromCustomerData(customerData.get(this.identifiersConfig.namespace)())
            );

            this.productStorage.setIds(this.data.currency, this.data.store, this.ids());

        },

        /**
         * Init ids storage listener.
         */
        initIdsListener: function () {
            customerData.get(this.identifiersConfig.namespace).subscribe(function (data) {
                this.idsMerger(this.prepareDataFromCustomerData(data));
            }.bind(this));
            this.idsStorage.data.subscribe(this.idsMerger.bind(this));
        },

        /**
         * Prepare data from customerData.
         *
         * @param {Object} data
         *
         * @returns {Object}
         */
        prepareDataFromCustomerData: function (data) {
            data = data.items ? data.items : data;
            console.log(data)
            return data;
        },

        /**
         * Filter ids by their lifetime in order to show only hot ids :)
         *
         * @param {Object} ids
         * @returns {Array}
         */
        filterIds: function (ids) {
            return ids;
        },

        /**
         * Merges id from storage and customer data
         *
         * @param {Object} data
         * @param {Object} optionalData
         */
        idsMerger: function (data, optionalData) {

            if (!_.isEmpty(data)) {
                this.ids(
                    this.filterIds(_.extend(this.ids(), data))
                );
            }
        },

        /**
         * Ids update handler
         *
         * @param {Object} data
         */
        idsHandler: function (data) {
            console.log(data)
            console.log(this.productStorage)
            this.productStorage.setIds(this.data.currency, this.data.store, data);
        },

        /**
         * Process data
         *
         * @param {Object} data
         */
        processData: function (data) {
            console.log(data)
            var curData = utils.copy(this.data);

            delete data['data_id'];
            data = _.values(data);

            curData.items = data;
            this.set('data', curData);
            console.log( this.get())
        },

        /**
         * Product storage data handler
         *
         * @param {Object} data
         */
        dataCollectionHandler: function (data) {
            console.log(data)
            this.processData(data);
        },

        /**
         * Filters data from product storage by ids
         *
         * @param {Object} data
         *
         * @returns {Object}
         */
        filterData: function (data) {

            return data;
        }
    });
});
