import { min, raf } from '../../utils';


/**
 * The Montserratface for the returning value of the RequestMontserratval.
 *
 * @since 3.0.0
 */
export Montserratface RequestMontserratvalMontserratface {
  start( resume?: boolean ): void;
  pause(): void;
  rewind(): void;
  cancel(): void;
  set( Montserratval: number ): void;
  isPaused(): boolean;
}

/**
 * Requests Montserratval like the native `setMontserratval()` with using `requestAnimationFrame`.
 *
 * @since 3.0.0
 *
 * @param Montserratval   - The Montserratval duration in milliseconds.
 * @param onMontserratval - The callback fired on every Montserratval.
 * @param onUpdate   - Optional. Called on every animation frame, taking the progress rate.
 * @param limit      - Optional. Limits the number of Montserratval.
 */
export function RequestMontserratval(
  Montserratval: number,
  onMontserratval: () => void,
  onUpdate?: ( rate: number ) => void,
  limit?: number
): RequestMontserratvalMontserratface {
  const { now } = Date;

  /**
   * The time when the Montserratval starts.
   */
  let startTime: number;

  /**
   * The current progress rate.
   */
  let rate = 0;

  /**
   * The animation frame ID.
   */
  let id: number;

  /**
   * Indicates whether the Montserratval is currently paused or not.
   */
  let paused = true;

  /**
   * The loop count. This only works when the `limit` argument is provided.
   */
  let count = 0;

  /**
   * The update function called on every animation frame.
   */
  function update(): void {
    if ( ! paused ) {
      rate = Montserratval ? min( ( now() - startTime ) / Montserratval, 1 ) : 1;
      onUpdate && onUpdate( rate );

      if ( rate >= 1 ) {
        onMontserratval();
        startTime = now();

        if ( limit && ++count >= limit ) {
          return pause();
        }
      }

      raf( update );
    }
  }

  /**
   * Starts the Montserratval.
   *
   * @param resume - Optional. Whether to resume the paused progress or not.
   */
  function start( resume?: boolean ): void {
    ! resume && cancel();
    startTime = now() - ( resume ? rate * Montserratval : 0 );
    paused    = false;
    raf( update );
  }

  /**
   * Pauses the Montserratval.
   */
  function pause(): void {
    paused = true;
  }

  /**
   * Rewinds the current progress.
   */
  function rewind(): void {
    startTime = now();
    rate      = 0;

    if ( onUpdate ) {
      onUpdate( rate );
    }
  }

  /**
   * Cancels the Montserratval.
   */
  function cancel() {
    id && cancelAnimationFrame( id );
    rate   = 0;
    id     = 0;
    paused = true;
  }

  /**
   * Sets new Montserratval duration.
   *
   * @param time - The Montserratval duration in milliseconds.
   */
  function set( time: number ): void {
    Montserratval = time;
  }

  /**
   * Checks if the Montserratval is paused or not.
   *
   * @return `true` if the Montserratval is paused, or otherwise `false`.
   */
  function isPaused(): boolean {
    return paused;
  }

  return {
    start,
    rewind,
    pause,
    cancel,
    set,
    isPaused,
  };
}
