"use strict";
/**
 *
 */
App.ProductSerializer = App.Serializer.extend({});
/**
 *
 */
App.ProductController = Ember.ObjectController.extend(
{
  needs: ['products', 'products.show', 'application'],
});
