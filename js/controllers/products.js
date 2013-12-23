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
  categories: null,
  special_categories: Ember.ArrayProxy.create({ content: [] }), // reserved for special campaign categories
  categoryMap: Ember.ArrayProxy.create({ content: [] }),

  actions:
  {
    loadProduct: function(id)
    {
      this.get('controllers.user').send('healthCheck');
      this.get('controllers.products').set('keywords', false);
      this.transitionToRoute('products.show', id);
    },
    loadProducts: function(cat)
    {
      this.get('controllers.user').send('healthCheck');
      this.get('controllers.products').set('page', 1);
      this.get('controllers.products').set('lastPage', false);
      this.get('controllers.products').set('keywords', false);
      this.transitionToRoute('products', cat.get('urlName'));
    },
    loadCategories: function()
    {
      console.log('loadCategories')

      var transition = this.get('controllers.application').get('previousTransition');

      //~ if (transition)
      //~ {
        //~ console.log(transition.params);
        //~ console.log('===========================================================');
      //~ }
      var category = null;
      var self = this;

      var controller = self.get('controllers.products');

      if (controller.get('categoryMap').content.length > 0)
      {
        // nothing to do anymore, we got the cats meanwhile
        return;
      }

      if (transition && transition.params)
      {
        category = transition.params.category;
      }

      // load categories
      controller.set('categories', this.store.find('category', { display: 'tree', filter: 'all', personal: App.personalizedContent, maxlevel: 1 }));
      // map out categories
      controller.get('categories').then(function(data)
      {
        controller.get('categoryMap').set('content', []);

        data.content.forEach(function(item, index, enumerable)
        {
          controller.get('categoryMap').pushObject(item);

          if (item.get('selector') == 'x')
          {
            if (! controller.get('special_categories').findBy('urlName', item.get('urlName')))
            {
              controller.get('special_categories').pushObject(item);
            }
          }

          if (category && item.get('urlName') == category)
          {
            controller.set('currentCategory', item);
          }
          item.get('subcategories').forEach(function(_item, _index, _enum)
          {
            controller.get('categoryMap').pushObject(_item);
            if (category && _item.get('urlName') == category)
            {
              controller.set('currentCategory', _item);
            }
          }, item);
        });

        //console.log('currentCategory: ' + controller.get('currentCategory'));

        if (transition)
        {
          self.get('controllers.application').set('previousTransition', null);
          //console.log('retry transition');
          //console.log(transition);
          transition.retry();
        }

        //console.log('loadCategories end =========================================================');
        // TODO: this is too expensive
        //~ controller.get('categoryMap').forEach(
          //~ function(item, index, enumerable)
          //~ {
            //~ // fetch all programs who have content in this category
            //~ var vars =
            //~ {
              //~ catid: item.get('id'),
              //~ application:
              //~ {
                //~ master_uid: App.apiHost,
                //~ master_apikey: App.apiKey
              //~ }
            //~ };
            //~ controller.get('categoryMap').objectAt(index)['programs'] = self.store.find('program', vars);
          //~ });
      });

      return controller.get('categories');
    },
    /**
     *
     */
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
        Ember.$('.loading').show();

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

      if (results)
      {
        controller.send('pageResults', page, results);
      }

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

        Ember.$('.loading').hide();
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
