/**
 * Master Glome App
 */
var GlomeApp = Ember.Application.extend(
{
  // configuration
  apiHost: 'http://catalogue.glome.me',
  apiKey: '299ab7cecf188740f3e611f8d0c9de75',
  // do not change
  loginPost: '/api/users/login.json',
  logoutGet: '/api/users/logout.json',
  productsIndex: '/api/products.json',
  generateGlomeIdPost: '/api/applications/generate_glomeid.json'
});

/**
 * Current App
 */
var App = GlomeApp.create(
{
  LOG_TRANSITIONS: true, // basic logging of successful transitions
  LOG_TRANSITIONS_INTERNAL: true, // detailed logging of all routing steps
  LOG_ACTIVE_GENERATION: true,
});

/**
 *
 */
App.Serializer = DS.RESTSerializer.extend(
{
  normalizePayload: function(type, payload)
  {
    return payload;
  },
  extractSingle: function(store, type, payload, id, requestType)
  {
    var o = {};
    switch (type)
    {
      case App.User:
        o['user'] = payload;
        break;
      case App.Product:
        o['product'] = payload;
        break;
      case App.Category:
        o['categories'] = payload;
        break;
      case App.Pairing:
        o['pairing'] = payload;
        break;
      case App.Action:
        // recorded actions have no ids
        id = 0;
        o['action'] = payload;
        break;
      case App.Program:
        o['program'] = payload;
        break;
      case App.Sync:
        console.log('payload: |' + payload + '|');
        console.log('payload c: |' + payload.length + '|');
        if (payload.length == 0)
        {
          payload = [];
        }
        console.log('payload: |' + payload + '|');
        id = null;
        o['sync'] = payload;
        break;
      default:
        alert('Unhandled type in extractSingle: ' + type);
    };
    return this._super(store, type, o, id, requestType);
  },
  extractArray: function(store, primaryType, payload)
  {
    var o = {};
    switch (primaryType)
    {
      case App.User:
        o['users'] = payload;
        break;
      case App.Product:
        o['products'] = payload;
        break;
      case App.Category:
        o['categories'] = payload;
        break;
      case App.Pairing:
        o['pairing'] = payload;
        break;
      case App.Action:
        id = 0;
        o['action'] = payload;
        break;
      case App.Program:
        o['program'] = payload;
        break;
      case App.Sync:
        o['sync'] = payload;
        break;
      default:
        alert('Unhandled type in extractArray: ' + primaryType);
    };
    return this._super(store, primaryType, o);
  }
});


/**
 *
 */
App.Adapter = DS.RESTAdapter.extend(
{
  host: App.apiHost,
  namespace: 'api',
  corsWithCredentials: true,
  // all REST calls should end with .json
  buildURL: function(type, id)
  {
    var url = ''
    url = this._super(type, id) + '.json';
    console.log('return URL for type: ' + type + ', id: ' + id + '. URL is: ' + url);
    return url;
  },
  // the server does not return JSON API compatible results (missing root)
  //~ find: function(store, type, id)
  //~ {
    //~ console.log('find called for ' + type);
//~
    //~ if (type == 'App.Sync')
    //~ {
      //~ return this.ajax(this.buildURL(type.typeKey, id), 'GET').then(function(data)
      //~ {
        //~ item = {''};
        //~ console.log('load syncs');
        //~ console.log(data);
        //~ if (data.length)
        //~ {
          //~ item = data;
        //~ }
        //~ return item;
      //~ });
    //~ }
    //~ else
    //~ {
      //~ return this.ajax(this.buildURL(type.typeKey, id), 'GET');
    //~ }
  //~ },
  // the server does not return JSON API compatible results (missing root)
  findAll: function(store, type, sinceToken)
  {
    //console.log('find all called for ' + type);
    return this._super(store, type, sinceToken).then(function(data)
    {
      var items = [];
      data.forEach(function (item)
      {
        items.push(item);
      });

      return items;
    });
  },
  findQuery: function(store, type, query)
  {
    var res = null;

    //console.log('findQuery called for ' + type + ', glomeid: ' + query['glomeid']);

    if (type == 'App.Sync')
    {
      res = this.ajax(this.buildURL(type.typeKey, query['glomeid']), 'GET', { data: query });
    }
    else
    {
      res = this._super(store, type, query);
    }

    return res.then(function(data)
    {
      //console.log(data);
      var items = [];
      data.forEach(function (item)
      {
        //console.log(item);
        if(item)
        {
          items.push(item);
        }
      });
      return items;
    });
  }
});

/**
 *
 */
App.Store = DS.Store.extend(
{
  adapter: App.Adapter
});

/**
 *
 */
DS.RawTransform = DS.Transform.extend(
{
  deserialize: function(deserialized)
  {
    return deserialized;
  },

  serialize: function(serialized)
  {
    return serialized;
  }
});

/**
 *
 */
App.register('transform:raw', DS.RawTransform);

/**
 * Log errors within promises
 */
Ember.RSVP.configure('onerror', function(error)
{
  Ember.Logger.assert(false, error);
});
