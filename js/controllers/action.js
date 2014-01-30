/**
 *
 */
App.ActionSerializer = App.Serializer.extend({});

App.ActionAdapter = App.Adapter.extend(
{
  createRecord: function(store, type, record)
  {
    var data = { user: {glomeid: record.glomeid} };
    return this.ajax(this.buildURL(type.typeKey, record), "POST", { data: data });
  },
  buildURL: function(type, record)
  {
    var url = App.apiHost + '/api/';

    var action = record.get('action');
    var kind = record.get('kind');

    switch (kind)
    {
      case 'a':
        url += 'ads/';
        break;
      case 'p':
        url += 'products/';
        break;
    }
    url += record.get('subject_id') + '/' + action + '.json'
    console.log('return URL for type: ' + type + ', action: ' + action + ', kind: ' + kind + '. URL is: ' + url);
    return url;
  },
});

/**
 *
 */
App.ActionController = Ember.ObjectController.extend(
{
  needs: ['user', 'application', 'products', 'products.show'],

  actions:
  {
    getit: function(id)
    {
      var self = this;

      var action = this.store.createRecord('action',
      {
        kind: 'p',
        action: 'getit',
        subject_id: id,
        glomeid: self.get('controllers.application').get('glomeid')
      });

      console.log(action);

      var res = action.save().then(
        function(data)
        {
          console.log('Registered getit on product.');
        },
        function(error)
        {
          console.log('Error registering getit action: ' + error.responseJSON.error);
        }
      );
    }
  }
});
