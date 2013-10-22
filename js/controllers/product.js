"use strict";
/**
 *
 */
App.ProductSerializer = App.Serializer.extend({});
/**
 *
 */
App.ProductController = Ember.ObjectController.extend(
{
  needs: ['products', 'products.show', 'application'],
  actions:
  {
    cashBack: function(product_id)
    {
      var glome_id = this.get('controllers.application').get('glomeid');
      console.log(glome_id + ' clicked on product cashback: ' + product_id);

      if (glome_id && product_id)
      {
        var url = App.apiHost + '/api/products/' + product_id + '/click/' + glome_id + '.json'

        return Ember.$.getJSON(url).then(function(data)
        {
          console.log(data);
          window.open(data.url, '_blank');
        });
      }
    }
  }
});
