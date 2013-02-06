// $Id: form.js 7448 2007-06-15 12:01:37Z andrew $
/**
 * @fileoverview Zapatec Form widget. Include this file in your HTML page.
 * Includes Zapatec Form modules: zpform.js, field.js, validator.js, utils.js.
 *
 * <pre>
 * Copyright (c) 2004-2006 by Zapatec, Inc.
 * http://www.zapatec.com
 * 1700 MLK Way, Berkeley, California,
 * 94709, U.S.A.
 * All rights reserved.
 * </pre>
 */

/**
 * Get path to this script
 * @private
 */

Zapatec.formPath = Zapatec.getPath("Zapatec.FormWidget");

// Include required scripts
Zapatec.Transport.include(Zapatec.formPath + '../lang/eng.js');
Zapatec.Transport.include(Zapatec.formPath + 'form-core.js', "Zapatec.Form");
Zapatec.Transport.include(Zapatec.formPath + 'form-field.js', "Zapatec.Form.Field");
Zapatec.Transport.include(Zapatec.formPath + 'form-validator.js');
Zapatec.Transport.include(Zapatec.formPath + 'form-utils.js');
