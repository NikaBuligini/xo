(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  global.Xo = factory();
}(this, function () { 'use strict';

  var config = {
    debug: false
  };


  function Xo (options) {
    this._init(options);
  }

  Xo.prototype._init = function (options) {
    this.$socket = io('localhost:3000');

    this.user = {
      id: $('#user_id').val(),
      name: $('#user_name').val()
    }

    $.ajaxSetup({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    Vue.config.debug = true;

    options = mergeOptions(this.constructor.options, options || {});

    if (!this.user.id || !this.user.name) {
      console.warn('User credentials are not defined in html.', this.user);
    }

    // var vueApp = Vue({
    //
    // });
    console.log(options);
  }

  function mergeOptions(parent, child) {
    var property;
    for (property in child) {
      if (child.hasOwnProperty(property)) {
        parent[property] = child[property];
      }
    }
    return parent;
  }

  Xo.version = '0.0.1';

  // default options
  Xo.options = {
    modules: []
  };

  return Xo;

}));
