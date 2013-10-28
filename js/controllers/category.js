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
  needs: ['user'],

  actions:
  {
    show: function(product)
    {
      this.transitionToRoute('product', product);//'product', product.id);
    },
    toggle: function(id)
    {
      this.get('controllers.user').send('healthCheck');

      Ember.$('ul[data-catid=' + id + ']').prev().toggleClass('down');
      Ember.$('ul[data-catid=' + id + ']').toggle();
    }
  }
});
