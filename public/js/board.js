(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  global.Board = factory();
}(this, function () { 'use strict';

  var config = {
    debug: false
  };


  function Board (options) {
    this._init(options);
  }

  Board.prototype._init = function (options) {
    options = mergeOptions(this.constructor.options, options || {});
    console.log(options);

    if (!options.el) {
      console.warn('\'el\' element is not defined.');
    }

    this.$el = $(options.el);

    this.table = [];

    var spot1 = new Spot();
    var spot2 = new Spot();
    var spot3 = new Spot();
    var spot4 = new Spot();
    var spot5 = new Spot();
    var spot6 = new Spot();
    var spot7 = new Spot();
    var spot8 = new Spot();
    var spot9 = new Spot();

    spot1.connect('right', spot2).connect('bottom', spot4).connect('bottomRight', spot5);
    spot2.connect('right', spot3).connect('bottom', spot5).connect('bottomRight', spot6);
    spot3.connect('bottom', spot6);

    spot4.connect('right', spot5).connect('bottom', spot7).connect('bottomRight', spot8);
    spot5.connect('right', spot6).connect('bottom', spot8).connect('bottomRight', spot9);
    spot6.connect('bottom', spot9);

    spot7.connect('right', spot8);
    spot8.connect('right', spot9);

    this.table.push(spot1);
    this.table.push(spot2);
    this.table.push(spot3);
    this.table.push(spot4);
    this.table.push(spot5);
    this.table.push(spot6);
    this.table.push(spot7);
    this.table.push(spot8);
    this.table.push(spot9);
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

  function Spot(id) {
    this.neighbours = {
      up: null,
      right: null,
      down: null,
      left: null,
      topLeft: null,
      topRight: null,
      bottomRight: null,
      bottomLeft: null
    };
    this.id = id;
    return this;
  }

  Spot.prototype.connect = function (side, spot) {
    switch (side) {
      case 'up':
        this.neighbours.up = spot;
        spot.neighbours.down = this;
        break;
      case 'right':
        this.neighbours.right = spot;
        spot.neighbours.left = this;
        break;
      case 'down':
        this.neighbours.down = spot;
        spot.neighbours.up = this;
        break;
      case 'left':
        this.neighbours.left = spot;
        spot.neighbours.right = this;
        break;
      case 'topLeft':
        this.neighbours.topLeft = spot;
        spot.neighbours.bottomRight = this;
        break;
      case 'topRight':
        this.neighbours.topRight = spot;
        spot.neighbours.bottomLeft = this;
        break;
      case 'bottomRight':
        this.neighbours.bottomRight = spot;
        spot.neighbours.topLeft = this;
        break;
      case 'bottomLeft':
        this.neighbours.bottomLeft = spot;
        spot.neighbours.topRight = this;
        break;
    }

    return this;
  }

  Board.version = '0.0.1';

  // default options
  Board.options = {
    el: false,
    modules: []
  };

  return Board;

}));
