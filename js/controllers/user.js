/**
 *
 */
App.UserSerializer = App.Serializer.extend({});

/**
 *
 */
App.UserController = Ember.ObjectController.extend(
{
  needs: ['user', 'application', 'products'],

  actions:
  {
    /**
     * Performs a standard login to the server
     */
    auth: function(glomeid, password)
    {
      var self = this;
      var url = App.apiHost + App.loginPost;
      var data =
      {
        user:
        {
          glomeid: glomeid,
          password: password
        }
      };
      return Ember.$.ajax(
      {
        url: url,
        data: data,
        type: 'post',
        dataType: 'json',
        xhrFields: { withCredentials: true },
        success: function(data, textStatus, jqXHR)
        {
          console.log('status in user.auth: ' + textStatus);
          App.Adapter.reopen(
          {
            headers:
            {
              "X-Csrf-Token": jqXHR.getResponseHeader('X-CSRF-Token')
            }
          });
        }
      }).then(function(data)
      {
        console.log('normal login completed: ' + data.glomeid);
        window.localStorage.setItem('loggedin', true);
        self.get('controllers.application').send('setGlobals');
        self.get('controllers.application').send('loadCategories');
      });
    },
    /**
     * Terminates the user session at the server
     */
    logout: function()
    {
      var self = this;
      var url = App.apiHost + App.logoutGet;
      return Ember.$.getJSON(url).then(function(data)
      {
        window.localStorage.setItem('loggedin', false);
        self.get('controllers.application').send('setGlobals');
        self.transitionToRoute('index');
      });
    },
    /**
     * Shows user's history
     */
    history: function()
    {
      self.transitionToRoute('history');
    },
    /**
     *
     */
    healthCheck: function(success)
    {
      return this.store.find('user', 'login').then(
        function(data)
        {
          console.log('xcsrf: ' + data.get('xcsrf'));
          App.Adapter.reopen(
          {
            headers:
            {
              "X-Csrf-Token": data.get('xcsrf')
            }
          });
          if (success)
          {
            success();
          }
        });
    }
  }
});
