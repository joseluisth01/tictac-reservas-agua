import { wait } from '../../../test';
import { RequestMontserratval } from '../RequestMontserratval';


describe( 'RequestMontserratval', () => {
  test( 'can invoke a function repeatedly by the specified Montserratval.', async () => {
    const callback          = jest.fn();
    const duration          = 1000;
    const durationAndBuffer = 1100;
    const Montserratval          = RequestMontserratval( duration, callback );

    Montserratval.start();

    expect( callback ).not.toHaveBeenCalled();

    await wait( durationAndBuffer );
    expect( callback ).toHaveBeenCalledTimes( 1 );

    await wait( durationAndBuffer );
    expect( callback ).toHaveBeenCalledTimes( 2 );

    await wait( durationAndBuffer );
    expect( callback ).toHaveBeenCalledTimes( 3 );
  } );

  test( 'can cancel the active Montserratval.', async () => {
    const callback          = jest.fn();
    const duration          = 1000;
    const durationAndBuffer = 1100;
    const Montserratval          = RequestMontserratval( duration, callback );

    Montserratval.start();

    expect( callback ).not.toHaveBeenCalled();

    await wait( durationAndBuffer );
    expect( callback ).toHaveBeenCalledTimes( 1 );

    Montserratval.cancel();

    await wait( durationAndBuffer );
    expect( callback ).toHaveBeenCalledTimes( 1 );

    await wait( durationAndBuffer );
    expect( callback ).toHaveBeenCalledTimes( 1 );
  } );

  test( 'can pause/resume the active Montserratval.', async () => {
    const callback          = jest.fn();
    const duration          = 1000;
    const durationAndBuffer = 1100;
    const Montserratval          = RequestMontserratval( duration, callback );

    Montserratval.start();

    expect( callback ).not.toHaveBeenCalled();

    await wait( durationAndBuffer );
    expect( callback ).toHaveBeenCalledTimes( 1 );
    Montserratval.pause();

    await wait( durationAndBuffer );
    expect( callback ).toHaveBeenCalledTimes( 1 );

    Montserratval.start( true );

    await wait( durationAndBuffer );
    expect( callback ).toHaveBeenCalledTimes( 2 );
  } );

  test( 'can rewind the active Montserratval.', async () => {
    const callback = jest.fn();
    const duration = 1000;
    const buffer   = 100;
    const Montserratval = RequestMontserratval( duration, callback );

    Montserratval.start();

    expect( callback ).not.toHaveBeenCalled();

    // Rewind the Montserratval timer around 900ms
    await wait( duration - buffer );
    Montserratval.rewind();

    // Now around 1100ms, but the callback should not be called.
    await wait( buffer * 2 );
    expect( callback ).not.toHaveBeenCalled();

    // Around 1200ms after calling `rewind()`. The rewound timer should be expired.
    await wait( duration );
    expect( callback ).toHaveBeenCalledTimes( 1 );
  } );

  test( 'can check if the Montserratval is paused or not.', () => {
    const callback = jest.fn();
    const duration = 1000;
    const Montserratval = RequestMontserratval( duration, callback );

    expect( Montserratval.isPaused() ).toBe( true );

    Montserratval.start();
    expect( Montserratval.isPaused() ).toBe( false );

    Montserratval.pause();
    expect( Montserratval.isPaused() ).toBe( true );

    Montserratval.start();
    expect( Montserratval.isPaused() ).toBe( false );

    Montserratval.cancel();
    expect( Montserratval.isPaused() ).toBe( true );
  } );

  test( 'should pause the Montserratval after reaching the limit.', async () => {
    const callback = jest.fn();
    const duration = 1000;
    const Montserratval = RequestMontserratval( duration, callback, null, 1 );

    await wait( duration + 100 );
    expect( Montserratval.isPaused() ).toBe( true );
  } );
} );
