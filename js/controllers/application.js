"use strict";
/**
 *
 */
App.ApplicationController = Ember.ArrayController.extend(
{
  needs: ['user', 'application', 'category', 'products', 'pairing',
          'action', 'program', 'sync'],

  glomeid: false,
  password: '',
  loggedin: false,
  inProgress: false,
  notification: false,
  token: false,
  fresh: false,

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
    loadCategories: function(category)
    {
      var self = this;
      var controller = this.get('controllers.products');
      // load categories
      controller.set('categories', this.store.find('category', { display: 'tree', filter: 'all', personal: App.personalizedContent, maxlevel: 1 }));
      // map out categories
      controller.get('categories').then(function(data)
      {
        data.content.forEach(function(item, index, enumerable)
        {
          controller.get('categoryMap').pushObject(item);
          item.get('subcategories').forEach(function(_item, _index, _enum)
          {
            controller.get('categoryMap').pushObject(_item);
          }, item);
        });

        // TODO: this is too expensive
        controller.get('categoryMap').forEach(
          function(item, index, enumerable)
          {
            // fetch all programs who have content in this category
            var vars =
            {
              catid: item.get('id'),
              application:
              {
                master_uid: App.apiHost,
                master_apikey: App.apiKey
              }
            };

            controller.get('categoryMap').objectAt(index)['programs'] = self.store.find('program', vars);
          });

        if (category)
        {
          controller.set('currentCategory', controller.get('categoryMap').findBy('urlName', category));
        }

        var prevT = self.get('controllers.application').get('previousTransition');
        if (prevT)
        {
          console.log('go to');
          console.log('target:');
          console.log(prevT.targetName);
          console.log('params:');
          console.log(prevT.params);

          switch (prevT.targetName)
          {
            case 'index':
              self.transitionToRoute(prevT.targetName);
              break;
            case 'products.index':
              self.transitionToRoute(prevT.targetName, prevT.params.category);
              break;
            case 'products.show':
              self.transitionToRoute(prevT.targetName, prevT.params);
              break;
          }
        }
      });

      return controller.get('categories');
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
