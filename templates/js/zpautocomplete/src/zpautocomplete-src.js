/**
 * @fileoverview Zapatec Auto Complete widget. Include this file in your HTML
 * page. Includes base Zapatec Auto Complete modules: zpautocomplete-core.js.
 *
 * <pre>
 * Copyright (c) 2004-2009 by Zapatec, Inc.
 * http://www.zapatec.com
 * 1700 MLK Way, Berkeley, California,
 * 94709, U.S.A.
 * All rights reserved.
 * </pre>
 */

/* $Id: zpautocomplete.js 15736 2009-02-06 15:29:25Z nikolai $ */

/**
 * Path to Zapatec Auto Complete scripts.
 * @private
 */
Zapatec.autoCompletePath = Zapatec.getPath('Zapatec.AutoCompleteWidget');

// Include required scripts
Zapatec.include(Zapatec.autoCompletePath + 'zpautocomplete-core.js', 'Zapatec.AutoComplete');
