"use strict";
/**
 *
 */
App.CategorySerializer = App.Serializer.extend({});

/**
 *
 */
App.CategoryController = Ember.ObjectController.extend(
{
  actions:
  {
    show: function(product)
    {
      this.transitionToRoute('product', product);//'product', product.id);
    },
    toggle: function(id)
    {
      Ember.$('ul[data-catid=' + id + ']').prev().toggleClass('down');
      Ember.$('ul[data-catid=' + id + ']').toggle();
    }
  }
});
