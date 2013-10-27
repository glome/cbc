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
  LOG_TRANSITIONS: false, // basic logging of successful transitions
  LOG_TRANSITIONS_INTERNAL: false, // detailed logging of all routing steps
  LOG_ACTIVE_GENERATION: false,
});

/**
 *
 */
App.Serializer = DS.RESTSerializer.extend(
{
  extractSingle: function(store, type, payload, id, requestType)
  {
    var o = {};
    switch (type)
    {
      case App.User:
        o['user'] = payload
        break;
      case App.Product:
        o['product'] = payload
        break;
      case App.Category:
        o['categories'] = payload
        break;
      case App.Pairing:
        o['pairing'] = payload
        break;
      case App.Action:
        // recorded actions have no ids
        id = 0;
        o['action'] = payload
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
        o['users'] = payload
        break;
      case App.Product:
        o['products'] = payload
        break;
      case App.Category:
        o['categories'] = payload
        break;
      case App.Pairing:
        o['pairing'] = payload
        break;
      case App.Action:
        o['action'] = payload
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
  findAll: function(store, type, sinceToken)
  {
    console.log('find all called for ' + type);
    return this._super(store, type, sinceToken).then(function(data)
    {
      var items = [];
      data.forEach(function (item)
      {
        items.push(item);
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
