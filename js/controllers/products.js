/**
 *
 */
App.ProductsController = Ember.ArrayController.extend(
{
  needs: ['user', 'application', 'product', 'products', 'products.show', 'action', 'program'],

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
      this.get('controllers.user').send('healthCheck');
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

      var catId = this.get('controllers.products').get('categoryMap').findBy('urlName', this.get('controllers.products').get('category')).get('id');

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
    },
    search: function(keywords, page, perPage)
    {
      this.get('controllers.products').set('loadingMore', true);

      this.set('page', 1);
      this.set('lastPage', false);
      Ember.$('div.product-grid').scrollTop(0);
      this.get('controllers.user').send('healthCheck');

      var results = this.store.findQuery('product', { keywords: keywords, page: page, per_page: perPage });
      this.get('controllers.products').set('results', results);
      this.transitionToRoute('products.search', 'all');

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
