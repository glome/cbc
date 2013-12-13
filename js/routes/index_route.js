/**
 * Redirect to products
 */
App.IndexRoute = Ember.Route.extend(
{
  beforeModel: function (transition)
  {
    var previousTransition = this.controllerFor('application').get('previousTransition');

    if (previousTransition)
    {
      this.controllerFor('products').send('loadCategories');
    }
  }
});