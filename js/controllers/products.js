/**
 *
 */
App.ProductsController = Ember.ArrayController.extend(
{
  needs: ['user', 'application', 'product', 'products', 'products.show', 'action', 'program'],

  page: 1,
  perPage: 24,
  lastPage: false,
  keywords: false,
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
      this.get('controllers.products').set('keywords', false);
      this.transitionToRoute('products.show', product);
    },
    loadProducts: function(cat)
    {
      this.get('controllers.user').send('healthCheck');
      this.get('controllers.products').set('page', 1);
      this.get('controllers.products').set('lastPage', false);
      this.get('controllers.products').set('keywords', false);
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
        if (controller.get('keywords'))
        {
          controller.send('search', controller.get('keywords'), nextPage, perPage);
        }
        else
        {
          controller.send('fetchPage', nextPage, perPage);
        }
      }
      return;
    },
    fetchPage: function(page, perPage)
    {
      var controller = this.get('controllers.products');
      controller.set('keywords', false);
      controller.set('loadingMore', true);

      var catId = this.get('controllers.products').get('categoryMap').findBy('urlName', this.get('controllers.products').get('category')).get('id');

      console.log('fetch more: ' + catId + ', page: ' + page);

      if (page == '' || ! page)
      {
        page = 1;
      }
      if (page == 1)
      {
        Ember.$('div.product-grid').scrollTop(0);
      }
      this.get('controllers.user').send('healthCheck');

      var results = this.store.find('product', { catid: catId, page: page });

      controller.send('pageResults', page, results);

      return;
    },
    search: function(keywords, page, perPage)
    {
      var controller = this.get('controllers.products');

      if (page == '' || ! page)
      {
        page = 1;
      }

      if (keywords != '')
      {
        controller.set('keywords', keywords);
      }

      this.get('controllers.user').send('healthCheck');

      controller.set('perPage', perPage);
      controller.set('lastPage', false);

      if (page == 1)
      {
        Ember.$('div.product-grid').scrollTop(0);
        this.transitionToRoute('products.search', 'all');
      }
      else
      {
        var results = this.store.findQuery('product', { keywords: keywords, page: page, per_page: perPage });
        controller.send('pageResults', page, results);
      }
      return;
    },
    pageResults: function(page, collection)
    {
      self = this;

      collection.then(function(data)
      {
        var lastPage = false;
        var controller = self.get('controllers.products');

        self.set('loadingMore', false);
        if (data.content.length)
        {
          if (data.content.length < controller.get('perPage'))
          {
            lastPage = true;
          }
          controller.set('page', page);
          controller.get('model').addObjects(collection);
        }
        else
        {
          lastPage = true
        }

        if (lastPage)
        {
          controller.set('lastPage', true);
        }
      });
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
