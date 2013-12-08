/**
 *
 */
App.ProductsRoute = Ember.Route.extend(
{
  subAction: 'index',

  model: function(params, transition)
  {
    var self = this;
    var data = null;

    console.log('ProductsRoute::model');

    var catMap = this.controllerFor('products').get('categoryMap');

    if (params.category)
    {
      this.controllerFor('products').set('category', params.category);

      if (catMap)
      {
        var cat = catMap.findBy('urlName', transition.params.category);
        this.controllerFor('products').set('currentCategory', cat);
      }
    }
    else
    {
      this.controllerFor('products').set('category', 'all');
    }

    switch (transition.targetName)
    {
      case 'products.index':
        this.subAction = 'index';
        var cur = this.controllerFor('products').get('currentCategory');
        if (cur)
        {
          catId = this.controllerFor('products').get('currentCategory').id
          console.log('catid for ' + params.category + ' is ' + catId);
          console.log('page: ' + this.controllerFor('products').get('page'));
          data = this.store.find('product', { catid: catId, page: this.controllerFor('products').get('page') });
          data.then(function()
          {
            if (window.matchMedia("(max-width: 767px)").matches)
            {
              Ember.$('ul.categories').slideUp();
            }
          });
        }
        else
        {
          transition.abort();
          this.transitionTo('index');
        }
        break;
      case 'products.search':
        this.subAction = 'search';

        var searchParams =
        {
          page: this.controllerFor('products').get('page'),
          perPage: this.controllerFor('products').get('perPage'),
          keywords: this.controllerFor('products').get('keywords')
        }
        data = this.store.findQuery('product', searchParams);

        if (! data)
        {
          transition.abort();
          this.transitionTo('index');
        }
        break;
      case 'products.show':
        this.subAction = 'show';
        if (transition.params.product_id)
        {
          this.product_id = transition.params.product_id;
        }
        else
        {
          this.product_id = transition['providedModels']['products.show']['id'];
        }
        data = this.store.find('product', parseInt(this.product_id));
        break;
    }
    return data;
  },
  setupController: function(controller, model)
  {
    console.log('ProductsRoute::setupController');
    if (this.product_id)
    {
      this.controllerFor('action').send('getit', this.product_id);
      this.controllerFor('products').set('currentProduct', model);

      if (this.controllerFor('products').get('category') == 'all')
      {
        this.controllerFor('products').set('currentCategory', this.store.find('category', parseInt(model.get('categories')[0].id)));
      }
    }
    else
    {
      this.controllerFor('products').set('currentProduct', null);
      this.controllerFor('products').set('model', model);
    }

    controller.set('programs', null);
    if (this.subAction != 'search' && controller.get('category') != 'all')
    {
      // load all programs that have content in this category
      controller.set('programs', this.controllerFor('products').get('categoryMap').findBy('urlName', controller.get('category'))['programs']);
    }

    model = [];
  },
  renderTemplate: function()
  {
    console.log('ProductsRoute::renderTemplate');
    if (this.subAction == 'show')
    {
      this.product_id = null;
    }
    this._super();
  }
});

App.ProductsIndexRoute = Ember.Route.extend({});

App.ProductsShowRoute = Ember.Route.extend({});

App.ProductsSearchRoute = Ember.Route.extend({});

