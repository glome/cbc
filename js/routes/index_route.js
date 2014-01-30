/**
 * Redirect to products
 */
App.IndexRoute = Ember.Route.extend(
{
  previousTransition: null,
  beforeModel: function (transition)
  {
    this.previousTransition = this.controllerFor('application').get('previousTransition');

    if (this.previousTransition)
    {
      this.controllerFor('products').send('loadCategories');
    }
  },
  renderTemplate: function()
  {
    if (! this.previousTransition)
    {
      this.render('intro', {into: 'application'});
    }
    else
    {
      this._super();
    }
  }
});