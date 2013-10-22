"use strict";
/**
 *
 */
App.ProductsController = Ember.ArrayController.extend(
{
  needs: ['user', 'application', 'product', 'products', 'products.show'],

  page: 1,
  perPage: 24,
  lastPage: false,
  loadingMore: false,
  currentCategory: null,
  currentProduct: null,
  category: '',
  categories: [],
  categoryMap: [],

  actions:
  {
    show: function(product)
    {
      this.transitionToRoute('products.show', product);
    },
    clickCategory: function(cat)
    {
      this.set('page', 1);
      this.set('lastPage', false);
      Ember.$('div.product-grid').scrollTop(0);
      console.log('clicked on cat: ' + cat.get('id'));
      this.transitionToRoute('products', cat.get('urlName'));
    },
    getMore: function()
    {
      if (this.get('loadingMore') || this.get('lastPage'))
      {
        return;
      }

      var controller = this.get('controllers.products');
      var nextPage = controller.get('page') + 1;
      var perPage = controller.get('perPage');

      if (! controller.get('lastPage'))
      {
        controller.send('fetchPage', nextPage, perPage);
      }
      return;
    },
    fetchPage: function(page, perPage)
    {
      this.get('controllers.products').set('loadingMore', true);

      var catId = this.get('controllers.products').get('categoryMap')[this.get('controllers.products').get('category')].id;

      console.log('fetch more: ' + catId + ', page: ' + page);
      var more = this.store.find('product', { catid: catId, page: page });

      self = this;
      more.then(function(data)
      {
        var lastPage = false;
        self.set('loadingMore', false);
        if (data.content.length)
        {
          if (data.content.length < self.get('controllers.products').get('perPage'))
          {
            lastPage = true;
          }
          self.get('controllers.products').set('page', page);
          self.get('controllers.products').get('model').addObjects(more);
        }
        else
        {
          lastPage = true
        }

        if (lastPage)
        {
          self.get('controllers.products').set('lastPage', true);
        }
      });
      return;
    }
  }
});

App.ProductsIndexController = Ember.ObjectController.extend(
{
  needs: ['user', 'application', 'products'],
});

App.ProductsShowController = Ember.ObjectController.extend(
{
  needs: ['user', 'application', 'products'],
});
