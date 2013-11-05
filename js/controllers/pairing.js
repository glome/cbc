/**
 *
 */
App.PairingSerializer = App.Serializer.extend({});

App.PairingAdapter = App.Adapter.extend(
{
  createRecord: function(store, type, record)
  {
    var data = {};
    var serializer = store.serializerFor(type.typeKey);

    serializer.serializeIntoHash(data, type, record, { includeId: true });

    return this.ajax(this.buildURL(type.typeKey, record.glomeid), "POST", { data: data });
  },
  buildURL: function(type, glomeid)
  {
    var url = App.apiHost + '/api/users/' + glomeid + '/sync/pair.json';

    console.log('return URL for type: ' + type + ', glomeid: ' + glomeid + '. URL is: ' + url);
    return url;
  },
});

/**
 *
 */
App.PairingController = Ember.ObjectController.extend(
{
  needs: ['user', 'application', 'products'],

  actions:
  {
    post: function()
    {
      var self = this;

      console.log('post: ' + self.get('controllers.application').get('glomeid'));

      var pairing = this.store.createRecord('pairing',
      {
        code_1: Ember.$('.pairing input[name="code_1"]').val(),
        code_2: Ember.$('.pairing input[name="code_2"]').val(),
        code_3: Ember.$('.pairing input[name="code_3"]').val(),
        glomeid: self.get('controllers.application').get('glomeid')
      });

      var res = pairing.save().then(
        function(data)
        {
          Ember.$('div.pairing .code .reply').html('Congratulations, your Glome wallet will be full of surprises! :)');
          Ember.$('div.pairing .code .reply').show().fadeOut(5000);
        },
        function(error)
        {
          console.log('pairing FAILED: ' + error.responseJSON.error);
          Ember.$('div.pairing .code .reply').html(error.responseJSON.error);
          Ember.$('div.pairing .code .reply').show().fadeOut(2000);
        }
      );
      return res;
    }
  }
});
