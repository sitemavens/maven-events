'use strict';

describe('Service: Forms', function () {

  // load the service's module
  beforeEach(module('gfMarketingApp'));

  // instantiate service
  var Forms;
  beforeEach(inject(function (_Forms_) {
    Forms = _Forms_;
  }));

  it('should do something', function () {
    expect(!!Forms).toBe(true);
  });

});
