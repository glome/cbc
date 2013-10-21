/**
 *
 */
App.ProductsRoute = Ember.Route.extend(
{
  beforeModel: function(transition)
  {
    console.log('ProductsRoute::beforeModel');
    return this.controllerFor('products').set('currentCategory', this.controllerFor('products').get('categoryMap')[transition.params.category]);
  },
  model: function(params, transition)
  {
    var data;

    console.log('ProductsRoute::model');

    this.controllerFor('products').set('category', params.category);

    switch (transition.targetName)
    {
      case 'products.index':
        var cur = this.controllerFor('products').get('currentCategory');
        var catId = this.controllerFor('products').get('currentCategory').id
        if (catId)
        {
          console.log('catid for ' + params.category + ' is ' + catId);
          console.log('page: ' + this.controllerFor('products').get('page'));
          data = this.store.find('product', { catid: catId, page: this.controllerFor('products').get('page') });
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
    console.log('category: ' + controller.get('category'));

    if (this.product_id)
    {
      console.log('model id exists');
      this.controllerFor('products').set('currentProduct', model);
      this.controllerFor('product').set('model', model);
      this.controllerFor('products').set('currentCategory', this.controllerFor('products').get('categoryMap')[controller.get('category')]);
      console.log('current cat: ' + this.controllerFor('products').get('currentCategory'));
      if (! this.controllerFor('products').get('currentCategory'))
      {
        console.log('reload categories due to missing currentCategory');
        this.controllerFor('application').send('loadCategories', controller.get('category'));
        //this.transitionTo('products.show', controller.get('category'), model.get('id'));
      }
    }
    else
    {
      console.log('model for products exists');
      this.controllerFor('products').set('currentProduct', null);
      this.controllerFor('products').set('model', model);
    }
  },
  renderTemplate: function()
  {
    console.log('ProductsRoute::renderTemplate');
    if (this.product_id)
    {
      console.log('render product');
      this.product_id = null;
      this._super();
      this.render('product');
    }
    else
    {
      console.log('render products');
      this._super();
    }
  },
});

/**
 *
 */
App.ProductsIndexRoute = Ember.Route.extend(
{
  model: function(params)
  {
    return this.controllerFor('products').get('model');
  },
  renderTemplate: function()
  {
    console.log('ProductsIndexRoute::renderTemplate');
    this.render('products');
  }
});
/**
 *
 */
App.ProductsShowRoute = Ember.Route.extend(
{
  renderTemplate: function()
  {
    console.log('ProductsShowRoute::renderTemplate');
    this.render('product');
  }
});
