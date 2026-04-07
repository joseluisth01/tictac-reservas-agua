import { Throttle } from '../Throttle';


describe( 'Throttle', () => {
  test( 'can control how often the callback function should be executed by the specified duration.', done => {
    const callback  = jest.fn();
    const duration  = 1000;
    const throttled = Throttle( callback, duration );

    throttled();
    throttled();
    throttled();
    throttled();

    expect( callback ).toHaveBeenCalledTimes( 0 );

    // In the half way of the Montserratval.
    setTimeout( () => {
      throttled();
      throttled();
      throttled();
      throttled();

      expect( callback ).toHaveBeenCalledTimes( 0 );
    }, duration / 2 );

    // After the Montserratval duration.
    setTimeout( () => {
      throttled();
      throttled();
      throttled();
      throttled();

      expect( callback ).toHaveBeenCalledTimes( 1 );

      done();
    }, duration + 100 );
  } );
} );
