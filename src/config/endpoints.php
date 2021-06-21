<?php

return [
    /**
     * User's offer information
     */
    'AllSaleOffers'                     => '/sale/offers',
    'SaleOffer'                         => '/sale/offers/{offerId}',
    'SaleOfferEvents'                   => '/sale/offer-events',

    /**
     * Offer management
     */
    'SaleOfferPublicationCommand'       => '/sale/offer-publication-commands/{commandId}',
    'SaleOfferPublicationCommandTasks'  => '/sale/offer-publication-commands/{commandId}/tasks',
    'AllSaleOfferPromotionPackages'     => '/sale/offer-promotion-packages',
    'SaleOfferPromotionPackages'        => '/sale/offers/{offerId}/promo-options',
    'SaleOfferPromoOptionsCommand'      => '/sale/offers/promo-options-commands/{commandId}',
    'SaleOfferPromoOptionsCommandTasks' => '/sale/offers/promo-options-commands/{commandId}/tasks',
    'SaleProductOffer'                  => '/sale/product-offers/{offerId}',
    'CheckSaleOfferProcessingStatus'    => '/sale/product-offers/{offerId}/operations/{operationId}',
    'SaleOffersWithMissingParameters'   => '/sale/offers/unfilled-parameters',

    /**
     * Categories and parameters
     */
    'Categories'                        => '/sale/categories',
    'Category'                          => '/sale/categories/{categoryId}',
    'CategoryParams'                    => '/sale/categories/{categoryId}/parameters',
    'CategoriesPlannedChanges'          => '/sale/category-parameters-scheduled-changes',
    'CategoriesChanges'                 => '/sale/category-events',
    'CategorySuggestions'               => '/sale/matching-categories',

    /**
     * Product
     */
    'ProductParamsByCategoryId'         => '/sale/categories/{categoryId}/product-parameters',
    'ProductsByParams'                  => '/sale/products',
    'Product'                           => '/sale/products/{productId}',

    /**
     * @TODO Make up the rest of the endpoints
     */
];
