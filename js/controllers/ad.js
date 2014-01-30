/**
 *
 */
App.AdSerializer = App.Serializer.extend({});
/**
 *
 */
App.AdController = Ember.ObjectController.extend(
{
  needs: ['products', 'application', 'program'],
  actions:
  {
    click: function(ad, redirect)
    {
      var glome_id = this.get('controllers.application').get('glomeid');

      if (glome_id && ad.id)
      {
        var url = App.apiHost + '/api/glomeads/' + ad.id + '/click/' + glome_id + '.json?redirect=' + redirect;

        return Ember.$.getJSON(url).then(function(data)
        {
          window.open(data.url, '_blank');
        });
      }
    }
  }
});
