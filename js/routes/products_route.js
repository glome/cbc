/**
 *
 */
App.ProductsRoute = Ember.Route.extend(
{
  model: function(params, transition)
  {
    var data = null;

    console.log('ProductsRoute::model');
    this.controllerFor('products').set('category', params.category);
    var catMap = this.controllerFor('products').get('categoryMap');

    if (catMap)
    {
      this.controllerFor('products').set('currentCategory', catMap[transition.params.category]);
    }

    switch (transition.targetName)
    {
      case 'products.index':
        var cur = this.controllerFor('products').get('currentCategory');
        if (cur)
        {
          catId = this.controllerFor('products').get('currentCategory').id
          console.log('catid for ' + params.category + ' is ' + catId);
          console.log('page: ' + this.controllerFor('products').get('page'));
          data = this.store.find('product', { catid: catId, page: this.controllerFor('products').get('page') });
        }
        else
        {
          transition.abort();
          this.transitionTo('index');
        }
        break;
      case 'products.show':
        if (transition.params.product_id)
        {
          this.product_id = transition.params.product_id;
        }
        else
        {
          this.product_id = transition['providedModels']['products.show']['id'];
        }
        console.log('load product: ' + this.product_id);
        data = this.store.find('product', parseInt(this.product_id));
        break;
    }

    return data;
  },
  setupController: function(controller, model)
  {
    console.log('ProductsRoute::setupController');
    console.log('model: ');
    console.log(model);
    var catid = this.controllerFor('products').get('categoryMap')[controller.get('category')].get('id');
    console.log('category: ' + controller.get('category') + ', id: ' + catid);

    if (this.product_id)
    {
      console.log('model id exists');
      this.controllerFor('action').send('getit', this.product_id);

      this.controllerFor('products').set('currentProduct', model);
//      this.controllerFor('products').set('currentCategory', this.controllerFor('products').get('categoryMap')[controller.get('category')]);
    }
    else
    {
      console.log('model for products exists');
      this.controllerFor('products').set('currentProduct', null);
      this.controllerFor('products').set('model', model);
    }

    model = [];

    // fetch all programs who have content in this category
    var vars =
    {
      catid: catid,
      application:
      {
        master_uid: App.apiHost,
        master_apikey: App.apiKey
      }
    };

    console.log(vars);

    var programs = this.store.find('program', vars).then(function(data)
    {
      controller.set('programs', data);
      console.log(data);
      //~ jQuery.each(data, function(index, value)
      //~ {
        //~ console.log(value);
      //~ });
    });
  },
  renderTemplate: function()
  {
    console.log('ProductsRoute::renderTemplate');
    if (this.product_id)
    {
      this.product_id = null;
    }
    this._super();
  }
});

App.ProductsIndexRoute = Ember.Route.extend({});

App.ProductsShowRoute = Ember.Route.extend({});


