/**
 *
 */
App.Router.map(function()
{
  this.route("history", { path: '/history' });
  this.resource("products", { path: '/products/:category' }, function()
  {
    this.route('show', { path: '/show/:product_id' });
  });
});
