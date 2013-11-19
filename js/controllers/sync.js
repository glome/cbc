/**
 *
 */
App.SyncSerializer = App.Serializer.extend({});

App.SyncAdapter = App.Adapter.extend(
{
  buildURL: function(type, glomeid)
  {
    console.log('type: ' + type);
    console.log('id: ' + glomeid);

    var url = App.apiHost + '/api/users/' + glomeid + '/sync.json';

    console.log('return URL for type: ' + type + ', glomeid: ' + glomeid + '. URL is: ' + url);
    return url;
  },
  createRecord: function(store, type, record)
  {
    var data = {};
    var serializer = store.serializerFor(type.typeKey);

    serializer.serializeIntoHash(data, type, record, { includeId: true });

    return this.ajax(this.buildURL(type.typeKey, record.glomeid), "POST", { data: data });
  }
});

/**
 *
 */
App.SyncController = Ember.ArrayController.extend(
{
  needs: ['user', 'application', 'products'],

  actions:
  {
    getSyncCode: function()
    {
      var self = this;
      var glomeid = self.get('controllers.application').get('glomeid');

      var fillCode = function(code)
      {
        Ember.$('div.pairing .code').find('button.getcode').hide();
        Ember.$('div.pairing .code').find('input.code').attr('disabled', 'disabled');

        Ember.$('div.pairing .code').find('input[name="code_1"]').val(code.substr(0, 4));
        Ember.$('div.pairing .code').find('input[name="code_2"]').val(code.substr(4, 4));
        Ember.$('div.pairing .code').find('input[name="code_3"]').val(code.substr(8, 4));

        console.log('Please enter this code: ' + code + ' in your Glome Wallet! :)');
      }

      var codes = this.store.findQuery('sync', {glomeid: glomeid}).then(function(data)
      {
        if (data.content.length)
        {
          fillCode(data.content[0].get('code'));
        }
        else
        {
          var sync = self.store.createRecord('sync',
          {
            glomeid: glomeid
          });

          var res = sync.save().then(
            function(data)
            {
              fillCode(data.get('code'));
            },
            function(error)
            {
              console.log('sync object creation FAILED: ' + error.responseJSON.error);
              Ember.$('div.pairing .code .reply').html(error.responseJSON.error);
              Ember.$('div.pairing .code .reply').show().fadeOut(2000);
            }
          );
        }
      });

      return codes;
    }
  }
});
