/**
 *
 */
App.ProductsRoute = Ember.Route.extend(
{
  subAction: 'index',

  beforeModel: function(transition)
  {
    console.log('ProductsRoute::beforeModel');
    if (transition.params.category)
    {
      this.controllerFor('products').set('category', transition.params.category);
      var catMap = this.controllerFor('products').get('categoryMap');
      cat = catMap.findBy('urlName', transition.params.category);

      if (transition.params.category != 'all' && typeof cat === 'undefined' && transition.targetName != 'products.search')
      {
        this.controllerFor('application').set('previousTransition', transition);
        this.transitionTo('index');
      }
      this.controllerFor('products').set('currentCategory', cat);
    }
  },
  model: function(params, transition)
  {
    var self = this;
    var data = null;
    var cat = false;
    var catMap = false;
    this.product_id = false;

    console.log('ProductsRoute::model');
    //~ console.log(transition.targetName);
    //~ console.log(transition.params);
    //~ console.log('------------------------------------------------------------');

    switch (transition.targetName)
    {
      case 'products.index':
        this.subAction = 'index';
        if (this.controllerFor('products').get('currentCategory'))
        {
          catId = this.controllerFor('products').get('currentCategory').id;
          data = this.store.find('product', { catid: catId, page: this.controllerFor('products').get('page') });
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

        if (transition['providedModelsArray'].length)
        {
          if (typeof transition['providedModels']['products.show']['_data'] != 'undefined')
          {
            this.product_id = transition['providedModels']['products.show']['_data']['id'];
          }
          else
          {
            this.product_id = transition['providedModels']['products.show']['product_id'];
          }
        }

        if (! this.product_id && typeof transition['params']['product_id'] != 'undefined')
        {
          // the last resort
          this.product_id = transition['params']['product_id'];
        }

        if (this.product_id)
        {
          data = this.store.find('product', this.product_id);
        }
        else
        {
          console.log('something is bad with transition:');
          console.log(transition);
        }
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
      if (model)
      {
        this.controllerFor('products').set('model', model);
      }
      else
      {
        this.transitionTo('index');
      }
    }

    //~ controller.set('programs', null);
    //~ if (this.subAction != 'search' && controller.get('category') != 'all')
    //~ {
      //~ // load all programs that have content in this category
      //~ controller.set('programs', this.controllerFor('products').get('categoryMap').findBy('urlName', controller.get('category'))['programs']);
    //~ }

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

