App.LoadingRoute = Ember.Route.extend(
{
  enter: function()
  {
    Ember.$('.loading').fadeIn('fast');
  },
  exit: function()
  {
    Ember.$('.loading').fadeOut('slow');
  },
  renderTemplate: function()
  {
    if (this.controllerFor('products').get('categoryMap').length > 0)
    {
      this.render('loading', {into: 'application'});
    }
    else
    {
      this._super();
    }
  }
});