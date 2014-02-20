/**
 *
 */
App.ApplicationRoute = Ember.Route.extend(
{
  actions:
  {
    error: function(reason, transition)
    {
      console.log('--------------------------------------- error ---------------------------------------------------');
      console.log(reason);
      console.log(transition);

      if (typeof reason.status !== 'undefined')
      {
        console.log('error status: ' + reason.status + ', transition: ' + transition);
        if (parseInt(reason.status) == 403)
        {
          this.controllerFor('application').set('previousTransition', transition);
          this.controllerFor('application').send('connect');
        }
      }

      Ember.$('.loading').hide();
      console.log('-------------------------------------------------------------------------------------------------');
    }
  },
  beforeModel: function(transition)
  {

    var self = this, promise, glomeid = window.localStorage.getItem('glomeid');
    // check our session
    if (glomeid)
    {
      promise = this.store.find('user', glomeid).then(function(data)
      {
        self.controllerFor('application').set('user', data);
        self.controllerFor('application').get('user').set('id', glomeid);
        if ( ! self.controllerFor('products').get('categories')
            && ! self.controllerFor('application').get('previousTransition'))
        {
          self.controllerFor('products').send('loadCategories', false);
        }
        //self.controllerFor('user').send('getEarnings');
      });
    }
    else
    {
      if (App.apiKey)
      {
        self.controllerFor('application').send('generateGlomeId');
      }
      else
      {
        console.log('It is not a Glome enabled application.');
      }
      promise = false;//this.store.find('user');
    }

    return promise;
  },
  model: function(params, transition)
  {
    console.log('ApplicationRoute::model');
  },
  setupController: function(controller, model)
  {
    console.log('ApplicationRoute::setupController');
    controller.get('controllers.application').send('setGlobals');

    if (App.glomeid && App.glomeid != '')
    {
      this.store.find('user', App.glomeid).then(function(data)
      {
        window.localStorage.setItem('loggedin', true);
        window.localStorage.setItem('glomeid', App.glomeid);
        controller.get('controllers.application').set('loggedin', true);
        //controller.get('controllers.user').send('getEarnings');
      });
    }

    controller.set('title', "Cashback Catalog");
    controller.set('categories', this.get('categories'));
    controller.set('contact', App.contact.email);
    controller.set('contactMailto', 'mailto: ' + App.contact.email + '?subject=' + App.contact.subject);
    controller.set('maintenance', App.maintenance || false);
  }
});