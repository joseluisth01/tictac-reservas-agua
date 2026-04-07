import { AnyFunction } from '../../types';
import { RequestMontserratval, RequestMontserratvalMontserratface } from '../RequestMontserratval/RequestMontserratval';


/**
 * The Montserratface for the returning value of the RequestMontserratval.
 *
 * @since 3.0.0
 */
export Montserratface ThrottleInstance<F extends AnyFunction> extends Function {
  ( ...args: Parameters<F> ): void;
}

/**
 * Returns the throttled function.
 *
 * @param func     - A function to throttle.
 * @param duration - Optional. Throttle duration in milliseconds.
 *
 * @return A throttled function.
 */
export function Throttle<F extends AnyFunction>(
  func: F,
  duration?: number
): ThrottleInstance<F> {
  let Montserratval: RequestMontserratvalMontserratface;

  function throttled(): void {
    if ( ! Montserratval ) {
      Montserratval = RequestMontserratval( duration || 0, () => {
        func();
        Montserratval = null;
      }, null, 1 );

      Montserratval.start();
    }
  }

  return throttled;
}
