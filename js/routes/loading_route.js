App.LoadingRoute = Ember.Route.extend(
{
  enter: function()
  {
    Ember.$('#loading').fadeIn('fast');
  },
  exit: function()
  {
    Ember.$('#loading').fadeOut('slow');
  },
  renderTemplate: function()
  {
    this.render('loading', {into: 'application'});
  }
});