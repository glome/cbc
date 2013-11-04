"use strict";
/**
 *
 */
App.ProgramSerializer = App.Serializer.extend({});

App.ProgramAdapter = App.Adapter.extend(
{
  buildURL: function(type, id)
  {
    var url = App.apiHost + '/api/programs.json';
    console.log('url: ' + url);
    return url;
  }
});

/**
 *
 */
App.ProgramController = Ember.ArrayController.extend(
{
  needs: ['ad', 'product', 'application'],

  actions:
  {
    openShop: function(program)
    {
      if (program.get('id'))
      {
        if (program.get('first_ad'))
        {
          this.get('controllers.ad').send('click', program.get('first_ad'), '/');
        }
        else
        {
          if (program.get('first_product'))
          {
            this.get('controllers.product').send('cashBack', program.get('first_product'), '/');
          }
        }
      }
    }
  }
});
