"use strict";
/**
 *
 */
App.ApplicationController = Ember.ArrayController.extend(
{
  needs: ['user', 'application', 'category', 'products', 'pairing',
          'action', 'program', 'sync'],

  user: false,
  glomeid: false,
  password: '',
  loggedin: false,
  inProgress: false,
  notification: false,
  token: false,
  fresh: false,
  previousTransition: null,

  actions:
  {
    /**
     * should be called from the application route before model
     */
    connect: function()
    {
      console.log('here we start?');
      if (! window.localStorage.getItem('loggedin'))
      {
        window.localStorage.setItem('loggedin', false);
      }

      if (window.localStorage.getItem('glomeid') && typeof window.localStorage.getItem('glomeid') != 'undefined')
      {
        console.log('glomeid exists: ' + window.localStorage.getItem('glomeid'));
        this.set('glomeid', window.localStorage.getItem('glomeid'));
        console.log('lets login');
        this.get('controllers.user').send('auth', this.get('glomeid'), this.get('password'));
      }
      else
      {
        if (App.apiKey)
        {
          this.send('generateGlomeId');
        }
        else
        {
          console.log('It is not a Glome enabled application.');
        }
      }
    },
    /**
     * Shows a notification message
     */
    notify: function(message)
    {
      this.set('notification', message);
      Ember.$('div.server.notifications').fadeIn(400, function()
      {
        Ember.$('div.server.notifications').fadeOut(5000);
      });
    },
    /**
     * Set some variables used in all view
     */
    setGlobals: function()
    {
      this.set('inProgress', false);
      this.set('glomeid', window.localStorage.getItem('glomeid'));
      this.set('loggedin', window.localStorage.getItem('loggedin') == 'true');
      console.log('globals: glomeid: ' + this.get('glomeid') + ', loggedin: ' + this.get('loggedin'));
    },
    open: function()
    {
      console.log(this);
    },
    /**
     * Requests a new Glome ID from the server
     */
    generateGlomeId: function()
    {
      console.log('user:generateGlomeId');
      if (! App.apiKey || App.apiKey == '') return;
      var self = this;
      var url = App.apiHost + App.generateGlomeIdPost;
      var data =
      {
        application:
        {
          uid: App.apiHost,
          apikey: App.apiKey
        }
      };

      return Ember.$.ajax(
      {
        url: url,
        data: data,
        type: 'post',
        dataType: 'json',
        xhrFields: { withCredentials: true }
      }).then(function(data)
      {
        console.log('glome id created: ' + data.glomeid);
        window.localStorage.setItem('glomeid', data.glomeid);
        self.get('controllers.application').send('setGlobals');
        self.get('controllers.user').send('auth', data.glomeid, '');
      });
    },
    /**
     * Search: redirect to products
     */
    search: function(keywords)
    {
      this.get('controllers.products').set('page', 1);
      this.get('controllers.products').send('search', keywords, 1);
    }
  }
});
