/**
 * ------------------------------------------------------------------------
 * JA Nex Template for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

function equalHeightEx(els, selector) {
    els = $$_(els);
    if (!els || els.length < 2) return;
    var maxh = 0;
    var els_ = [];
    els.each(function(el, i){
        if (!el) return;
        els_[i] = getDeepestWrapperEx (el, selector);
        //els_[i] = el;
        var ch = els_[i].getCoordinates().height;
        maxh = (maxh < ch) ? ch : maxh;
    },this);

    els_.each(function(el, i) {
        if (!el) return;
        if (el.getStyle('padding-top')!=null && el.getStyle('padding-bottom')!=null) {
            if (maxh-el.getStyle('padding-top').toInt()-el.getStyle('padding-bottom').toInt() > 0) {
                el.setStyle('min-height', maxh-el.getStyle('padding-top').toInt()-el.getStyle('padding-bottom').toInt());
            }
        } else {
            if (maxh > 0) el.setStyle('min-height', maxh);
        }
    }, this);
}

function getDeepestWrapperEx (el, selector) {
    while (el.getElement(selector) != null) {
        el = el.getElement(selector);
    }
    return el;
}