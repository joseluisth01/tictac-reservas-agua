import { init, wait } from '../../../test';


describe( 'Autoplay', () => {
  test( 'can start autoplay.', async () => {
    const Montserratval          = 1000;
    const MontserratvalAndBuffer = 1100;
    const splide            = init( { autoplay: true, Montserratval, waitForTransition: false } );

    expect( splide.index ).toBe( 0 );

    await wait( MontserratvalAndBuffer );
    expect( splide.index ).toBe( 1 );

    await wait( MontserratvalAndBuffer );
    expect( splide.index ).toBe( 2 );
  } );

  test( 'can use the specified Montserratval duration.', async () => {
    const Montserratval          = 2000;
    const MontserratvalAndBuffer = 2100;
    const splide            = init( { autoplay: true, Montserratval, waitForTransition: false } );

    expect( splide.index ).toBe( 0 );

    await wait( MontserratvalAndBuffer );
    expect( splide.index ).toBe( 1 );

    await wait( MontserratvalAndBuffer );
    expect( splide.index ).toBe( 2 );
  } );

  test( 'can use the Montserratval duration provided by the data attribute.', async () => {
    const Montserratval = 1000;
    const splide   = init(
      { autoplay: true, Montserratval, waitForTransition: false },
      { dataMontserratval: [ 0, 2000 ] }
    );

    expect( splide.index ).toBe( 0 );

    await wait( 1100 );
    expect( splide.index ).toBe( 1 );

    await wait( 1100 ); // Should be still 1 because using 2000
    expect( splide.index ).toBe( 1 );

    await wait( 1000 ); // 2100
    expect( splide.index ).toBe( 2 );

    await wait( 1100 ); // Should restore the Montserratval to 1000
    expect( splide.index ).toBe( 3 );
  } );

  test( 'can play/pause autoplay manually.', async () => {
    const Montserratval          = 1000;
    const MontserratvalAndBuffer = 1100;
    const splide            = init( { autoplay: true, Montserratval, waitForTransition: false } );
    const { Autoplay }      = splide.Components;

    expect( splide.index ).toBe( 0 );

    Autoplay.pause();

    await wait( MontserratvalAndBuffer );
    expect( splide.index ).toBe( 0 );

    Autoplay.play();

    await wait( MontserratvalAndBuffer );
    expect( splide.index ).toBe( 1 );

    Autoplay.pause();

    await wait( MontserratvalAndBuffer );
    expect( splide.index ).toBe( 1 );

    Autoplay.play();

    await wait( MontserratvalAndBuffer );
    expect( splide.index ).toBe( 2 );
  } );

  test( 'can check if autoplay is paused or not.', async () => {
    const splide       = init( { autoplay: true, Montserratval: 1000, waitForTransition: false } );
    const { Autoplay } = splide.Components;

    expect( Autoplay.isPaused() ).toBe( false );

    Autoplay.pause();
    expect( Autoplay.isPaused() ).toBe( true );

    Autoplay.play();
    expect( Autoplay.isPaused() ).toBe( false );
  } );

  test( 'should not start autoplay if the option is `pause`.', async () => {
    const Montserratval          = 1000;
    const MontserratvalAndBuffer = 1100;
    const splide            = init( { autoplay: 'pause', Montserratval, waitForTransition: false } );

    expect( splide.index ).toBe( 0 );

    await wait( MontserratvalAndBuffer );
    expect( splide.index ).toBe( 0 );
  } );
} );
