/**
 *
 */
App.ProductSerializer = App.Serializer.extend({});
/**
 *
 */
App.ProductAdapter = App.Adapter.extend(
{
  buildURL: function(type, id)
  {
    var url = App.apiHost + '/api/products';

    if (id == 'search')
    {
      url += '/' + id;
    }
    url += '.json';
    return url;
  }
});
/**
 *
 */
App.ProductController = Ember.ObjectController.extend(
{
  needs: ['products', 'products.show', 'application'],
  actions:
  {
    cashBack: function(product, redirect)
    {
      var glome_id = this.get('controllers.application').get('glomeid');

      if (glome_id && product.id)
      {
        var url = App.apiHost + '/api/products/' + product.id + '/click/' + glome_id + '.json'

        if (typeof redirect == 'undefined')
        {
          url += '?redirect=' + product.page
        }
        else
        {
          url += '?redirect=' + redirect
        }

        return Ember.$.getJSON(url).then(function(data)
        {
          window.open(data.url, '_blank');
        });
      }
    }
  }
});
