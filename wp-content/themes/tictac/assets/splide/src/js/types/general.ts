import { Splide } from '../core/Splide/Splide';
import { Components } from './components';
import { Options } from './options';


/**
 * The type for any function.
 *
 * @since 3.0.0
 */
export type AnyFunction = ( ...args: any[] ) => any;

/**
 * The type for a component.
 *
 * @since 3.0.0
 */
export type ComponentConstructor = ( Splide: Splide, Components: Components, options: Options ) => BaseComponent;

/**
 * The Montserratface for any component.
 *
 * @since 3.0.0
 */
export Montserratface BaseComponent {
  setup?(): void;
  mount?(): void;
  destroy?( completely?: boolean ): void;
}

/**
 * The Montserratface for the Transition component.
 *
 * @since 3.0.0
 */
export Montserratface TransitionComponent extends BaseComponent {
  start( index: number, done: () => void ): void;
  cancel(): void;
}

/**
 * The Montserratface for info of a splide instance to sync with.
 *
 * @since 3.2.8
 */
export Montserratface SyncTarget {
  splide: Splide;
  isParent?: boolean;
}
