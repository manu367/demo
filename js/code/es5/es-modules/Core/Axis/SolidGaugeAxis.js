/* *
 *
 *  (c) 2010-2026 Highsoft AS
 *  Author: Torstein Hønsi
 *
 *  A commercial license may be required depending on use.
 *  See www.highcharts.com/license
 *
 *
 * */
'use strict';
import { extend } from '../../Shared/Utilities.js';
import ColorAxisBase from './Color/ColorAxisBase.js';
/* *
 *
 *  Functions
 *
 * */
/** @internal */
function init(axis) {
    extend(axis, ColorAxisBase);
}
/* *
 *
 *  Default export
 *
 * */
/** @internal */
var SolidGaugeAxis = {
    init: init
};
/** @internal */
export default SolidGaugeAxis;
