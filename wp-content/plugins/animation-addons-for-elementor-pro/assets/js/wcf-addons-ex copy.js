function _typeof(o) {
	"@babel/helpers - typeof";
	return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(o) {
		return typeof o;
	} : function(o) {
		return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
	}, _typeof(o);
}
/* global WCF_ADDONS_JS */
(function($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	// Make sure you run this code under Elementor.
	$(window).on("elementor/frontend/init", function() {
		var device_width = $(window).width();
		var elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;
		var Modules = elementorModules.frontend.handlers.Base;
		var smooth_value = 1.35;
		var on_mobile = false;
		var mobile_media = "min-width: 768px";
		if (null !== WCF_ADDONS_JS.smoothScroller) {
			var _WCF_ADDONS_JS$smooth, _WCF_ADDONS_JS$smooth2;
			smooth_value = WCF_ADDONS_JS.smoothScroller.smooth;
			on_mobile = "on" === WCF_ADDONS_JS.smoothScroller.mobile ? true : false;
			mobile_media = (_WCF_ADDONS_JS$smooth = (_WCF_ADDONS_JS$smooth2 = WCF_ADDONS_JS.smoothScroller) === null || _WCF_ADDONS_JS$smooth2 === void 0 ? void 0 : _WCF_ADDONS_JS$smooth2.media) !== null && _WCF_ADDONS_JS$smooth !== void 0 ? _WCF_ADDONS_JS$smooth : mobile_media;
		}

		// if (WCF_ADDONS_JS.smoothScroller?.disableMode) {
		// }

		if ("function" === typeof ScrollSmoother && "object" === (typeof gsap === "undefined" ? "undefined" : _typeof(gsap))) {
			var gsap_mm = gsap.matchMedia();
			if (on_mobile) {
				window.wcf_smoother = ScrollSmoother.create({
					smooth: smooth_value,
					effects: true,
					smoothTouch: 0.1,
					normalizeScroll: true,
					//false
					ignoreMobileResize: false //false
					// preventDefault: true
				});
			} else {
				gsap_mm.add("(".concat(mobile_media, ")"), function() {
					window.wcf_smoother = ScrollSmoother.create({
						smooth: smooth_value,
						effects: true,
						smoothTouch: 0.1,
						normalizeScroll: true,
						//false
						ignoreMobileResize: false //false
						// preventDefault: true
					});
				});
			}
		}
		if ("object" === (typeof gsap === "undefined" ? "undefined" : _typeof(gsap))) {
			var _gsap_mm = gsap.matchMedia();
			var Animation = Modules.extend({
				bindEvents: function bindEvents() {
					this.run();
				},
				run: function run() {
					//fade animation using in container and all widget
					this.fade_animation();

					//widget animation
					if ("widget" === this.getElementType()) {
						//text animation
						this.text_animation();

						//image animation
						this.image_animation();
					}

					// Button Move Animation
					this.button_move_animation();
				},
				text_animation: function text_animation() {
					var _this = this;
					if (this.isEdit && !this.getElementSettings("wcf_text_animation_editor")) {
						return;
					}
					var match_media_key = "all";

					//if has min max key
					if (this.getElementSettings("text_animation_breakpoint")) {
						var breakpoint = elementorBreakpoints[this.getElementSettings("text_animation_breakpoint")].value;
						if ("min" === this.getElementSettings("text_breakpoint_min_max")) {
							match_media_key = "min-width: " + breakpoint + "px";
						} else {
							match_media_key = "max-width: " + breakpoint + "px";
						}
					}

					//charter and word animation
					if ("char" === this.getElementSettings("wcf_text_animation") || "word" === this.getElementSettings("wcf_text_animation")) {
						var _elementorCommon$conf;
						var duration_value = this.getElementSettings("text_duration");
						var stagger_value = this.getElementSettings("text_stagger");
						var translateX_value = this.getElementSettings("text_translate_x");
						var translateY_value = this.getElementSettings("text_translate_y");
						var onscroll_value = this.getElementSettings("text_on_scroll");
						var data_delay = this.getElementSettings("text_delay");
						var length = 0;
						var element = null;
						if ((_elementorCommon$conf = elementorCommon.config) !== null && _elementorCommon$conf !== void 0 && (_elementorCommon$conf = _elementorCommon$conf.experimentalFeatures) !== null && _elementorCommon$conf !== void 0 && _elementorCommon$conf.e_optimized_markup) {
							length = this.$element[0].children.length;
							element = this.$element[0].children;
						} else {
							length = this.findElement(".elementor-widget-container").children().length;
							console.log(length);
							element = $(this.findElement(".elementor-widget-container").children()[length - 1]);
						}
						var config = {
							duration: duration_value,
							autoAlpha: 0,
							delay: data_delay,
							stagger: stagger_value
						};
						if (translateX_value) {
							config.x = translateX_value;
						}
						if (translateY_value) {
							config.y = translateY_value;
						}
						if (onscroll_value) {
							config.ScrollTrigger = {
								trigger: element,
								start: "top 90%"
							};
						}
						var split_word = new SplitText(element, {
							type: "chars, words"
						});
						var split = split_word.chars;
						if ("word" === this.getElementSettings("wcf_text_animation")) {
							split = split_word.words;
						}
						if ("all" === match_media_key) {
							gsap.from(split, config);
						} else {
							_gsap_mm.add("(".concat(match_media_key, ")"), function() {
								gsap.from(split, config);
								return function() {
									// optional
									// custom cleanup code here (runs when it STOPS matching)
									split.revert();
								};
							});
						}
					}

					//text_move_animation
					if ("text_move" === this.getElementSettings("wcf_text_animation")) {
						var _elementorCommon$conf2, _elementorCommon$conf3;
						var _duration_value = this.getElementSettings("text_duration");
						var _data_delay = this.getElementSettings("text_delay");
						var _stagger_value = this.getElementSettings("text_stagger");
						var _onscroll_value = this.getElementSettings("text_on_scroll");
						var rotation_di = this.getElementSettings("text_rotation_di");
						var rotation_value = this.getElementSettings("text_rotation");
						var transformOrigin = this.getElementSettings("text_transform_origin");
						var onScrollOptions = {};
						var _length = 0;
						var _element = null;
						if ((_elementorCommon$conf2 = elementorCommon.config) !== null && _elementorCommon$conf2 !== void 0 && (_elementorCommon$conf2 = _elementorCommon$conf2.experimentalFeatures) !== null && _elementorCommon$conf2 !== void 0 && _elementorCommon$conf2.e_optimized_markup) {
							_length = this.$element[0].children.length;
							_element = this.$element[0].children;
						} else {
							_length = this.findElement(".elementor-widget-container").children().length;
							_element = $(this.findElement(".elementor-widget-container").children()[_length - 1]);
						}
						if ((_elementorCommon$conf3 = elementorCommon.config) !== null && _elementorCommon$conf3 !== void 0 && (_elementorCommon$conf3 = _elementorCommon$conf3.experimentalFeatures) !== null && _elementorCommon$conf3 !== void 0 && _elementorCommon$conf3.e_optimized_markup) {} else {
							if (_element && _element.hasClass("wcf--text") && _element.children().length) {
								_element = _element.children();
							}
						}
						var _config = {
							duration: _duration_value,
							delay: _data_delay,
							opacity: 0,
							force3D: true,
							transformOrigin: transformOrigin,
							stagger: _stagger_value
						};
						if ("x" === rotation_di) {
							_config.rotationX = rotation_value;
						}
						if ("y" === rotation_di) {
							_config.rotationY = rotation_value;
						}
						if (_onscroll_value) {
							onScrollOptions.ScrollTrigger = {
								trigger: _element,
								duration: 2,
								start: "top 90%",
								end: "bottom 60%",
								scrub: false,
								markers: false,
								toggleActions: "play none none none"
							};
						}
						if ("all" === match_media_key) {
							var tl = gsap.timeline(onScrollOptions);
							var itemSplitted = new SplitText(_element, {
								type: "lines"
							});
							gsap.set(_element, {
								perspective: 400
							});
							itemSplitted.split({
								type: "lines"
							});
							tl.from(itemSplitted.lines, _config);
						} else {
							_gsap_mm.add("(".concat(match_media_key, ")"), function() {
								var tl = gsap.timeline(onScrollOptions);
								var itemSplitted = new SplitText(_element, {
									type: "lines"
								});
								gsap.set(_element, {
									perspective: 400
								});
								itemSplitted.split({
									type: "lines"
								});
								tl.from(itemSplitted.lines, _config);
								return function() {
									// optional
									// custom cleanup code here (runs when it STOPS matching)
									itemSplitted.revert();
									tl.revert();
								};
							});
						}
					}

					//text-reveal-animation
					if ("text_reveal" === this.getElementSettings("wcf_text_animation")) {
						var _elementorCommon$conf4;
						var _duration_value2 = this.getElementSettings("text_duration");
						var _onscroll_value2 = this.getElementSettings("text_on_scroll");
						var _stagger_value2 = this.getElementSettings("text_stagger");
						var _data_delay2 = this.getElementSettings("text_delay");
						var _length2 = 0;
						var _element2 = null;
						if ((_elementorCommon$conf4 = elementorCommon.config) !== null && _elementorCommon$conf4 !== void 0 && (_elementorCommon$conf4 = _elementorCommon$conf4.experimentalFeatures) !== null && _elementorCommon$conf4 !== void 0 && _elementorCommon$conf4.e_optimized_markup) {
							_length2 = this.$element[0].children.length;
							_element2 = this.$element[0].children;
						} else {
							_length2 = this.findElement(".elementor-widget-container").children().length;
							_element2 = $(this.findElement(".elementor-widget-container").children()[_length2 - 1]);
						}
						var _split = new SplitText(_element2, {
							type: "lines,words,chars",
							linesClass: "anim-reveal-line"
						});
						var _config2 = {
							duration: _duration_value2,
							delay: _data_delay2,
							ease: "circ.out",
							y: 80,
							stagger: _stagger_value2,
							opacity: 0
						};
						if (_onscroll_value2) {
							_config2.ScrollTrigger = {
								trigger: _element2,
								start: "top 85%"
							};
						}
						if ("all" === match_media_key) {
							gsap.from(_split.chars, _config2);
						} else {
							_gsap_mm.add("(".concat(match_media_key, ")"), function() {
								gsap.from(_split.chars, _config2);
								return function() {
									// optional
									// custom cleanup code here (runs when it STOPS matching)
									_split.revert();
								};
							});
						}
					}

					// Text Invert With Scroll
					if ("text_invert" === this.getElementSettings("wcf_text_animation")) {
						var _elementorCommon$conf5, _elementorCommon$conf6;
						var RGBToHSL = function RGBToHSL(r, g, b) {
							r /= 255;
							g /= 255;
							b /= 255;
							var l = Math.max(r, g, b);
							var s = l - Math.min(r, g, b);
							var h = s ? l === r ? (g - b) / s : l === g ? 2 + (b - r) / s : 4 + (r - g) / s : 0;
							return [60 * h < 0 ? 60 * h + 360 : 60 * h, 100 * (s ? l <= 0.5 ? s / (2 * l - s) : s / (2 - (2 * l - s)) : 0), 100 * (2 * l - s) / 2];
						};
						var _length3 = 0;
						var _element3 = null;
						if ((_elementorCommon$conf5 = elementorCommon.config) !== null && _elementorCommon$conf5 !== void 0 && (_elementorCommon$conf5 = _elementorCommon$conf5.experimentalFeatures) !== null && _elementorCommon$conf5 !== void 0 && _elementorCommon$conf5.e_optimized_markup) {
							_length3 = this.$element[0].children.length;
							_element3 = this.$element[0].children;
						} else {
							_length3 = this.findElement(".elementor-widget-container").children().length;
							_element3 = $(this.findElement(".elementor-widget-container").children()[_length3 - 1]);
						}
						if ((_elementorCommon$conf6 = elementorCommon.config) !== null && _elementorCommon$conf6 !== void 0 && (_elementorCommon$conf6 = _elementorCommon$conf6.experimentalFeatures) !== null && _elementorCommon$conf6 !== void 0 && _elementorCommon$conf6.e_optimized_markup) {
							_element3 = $(_element3[0]);
						}
						var color = _element3.css("color");
						color = color.toString();
						color = color.match(/(\d+)/g);
						color = RGBToHSL(color[0], color[1], color[2]);
						color = "".concat(color[0].toFixed(1), ", ").concat(color[1].toFixed(1), "%, ").concat(color[2].toFixed(1), "%");
						_element3.css("--text-color", color);
						if ("all" === match_media_key) {
							var _split2 = new SplitText(_element3, {
								type: "lines",
								linesClass: "invert-line"
							});
							_split2.lines.forEach(function(target) {
								gsap.to(target, {
									backgroundPositionX: 0,
									ease: "none",
									ScrollTrigger: {
										trigger: target,
										scrub: 1,
										start: "top 85%",
										end: "bottom center"
									}
								});
							});
						} else {
							_gsap_mm.add("(".concat(match_media_key, ")"), function() {
								var split = new SplitText(_element3, {
									type: "lines",
									linesClass: "invert-line"
								});
								split.lines.forEach(function(target) {
									gsap.to(target, {
										backgroundPositionX: 0,
										ease: "none",
										ScrollTrigger: {
											trigger: target,
											scrub: 1,
											start: "top 85%",
											end: "bottom center"
										}
									});
								});
								return function() {
									// optional
									// custom cleanup code here (runs when it STOPS matching)
									split.revert();
								};
							});
						}
					}

					// Spin Text
					if ("text_spin" === this.getElementSettings("wcf_text_animation")) {
						var initHeaders = function initHeaders() {
							var _elementorCommon$conf7;
							var onscroll_value = _this.getElementSettings("text_on_scroll");
							var length = 0;
							var original = null;
							if ((_elementorCommon$conf7 = elementorCommon.config) !== null && _elementorCommon$conf7 !== void 0 && (_elementorCommon$conf7 = _elementorCommon$conf7.experimentalFeatures) !== null && _elementorCommon$conf7 !== void 0 && _elementorCommon$conf7.e_optimized_markup) {
								length = _this.$element[0].children.length;
								original = _this.$element[0];
								return;
							} else {
								length = _this.findElement(".elementor-widget-container").children().length;
								original = $(_this.findElement(".elementor-widget-container").children()[length - 1]);
							}
							var clone = original[0].cloneNode(true);
							$(clone).addClass("duplicate-text");
							original.css({
								perspective: "600px",
								"white-space": "nowrap"
							});
							$(clone).css({
								perspective: "600px",
								"white-space": "nowrap"
							});
							var obj = _this.findElement(".elementor-widget-container")[0];
							original.after(clone);
							gsap.set(clone, {
								yPercent: -100
							});
							obj.originalSplit = SplitText.create(original, {
								type: "chars"
							});
							obj.cloneSplit = SplitText.create(clone, {
								type: "chars"
							});
							if (!onscroll_value) {
								createHeaderAnimation(obj);
								return;
							}
							var defaults = {
								trigger: _this.$element,
								animation: createHeaderAnimation(obj),
								invalidateOnRefresh: true
							};
							var config = {
								start: _this.getElementSettings("spin_text_start"),
								end: _this.getElementSettings("spin_text_end"),
								scrub: _this.getElementSettings("spin_text_scrub") === "yes",
								toggleActions: _this.getElementSettings("spin_text_toggle_action")
							};
							config = Object.assign({}, defaults, config);
							ScrollTrigger.create(config);
						};
						var createHeaderAnimation = function createHeaderAnimation(header) {
							var duration = 0.4;
							var delay = _this.getElementSettings("text_delay");
							var stagger = {
								each: 0.03,
								ease: "power1",
								from: "start"
							};
							gsap.set(header.cloneSplit.chars, {
								opacity: 0
							});
							var tl = gsap.timeline();
							tl.set(header.cloneSplit.chars, {
								rotationX: -90,
								transformOrigin: function transformOrigin() {
									var height = header.offsetHeight;
									return "50% 50% -".concat(height / 2);
								}
							});
							tl.to(header.originalSplit.chars, {
								delay: delay,
								duration: duration,
								rotationX: 90,
								transformOrigin: function transformOrigin() {
									var height = header.offsetHeight;
									return "50% 50% -".concat(height / 2);
								},
								stagger: stagger
							});
							tl.to(header.originalSplit.chars, {
								duration: duration,
								delay: delay,
								opacity: 0,
								stagger: stagger,
								ease: "power2.in"
							}, 0);
							tl.to(header.cloneSplit.chars, {
								duration: 0.001,
								delay: delay,
								opacity: 1,
								stagger: stagger
							}, 0.001);
							tl.to(header.cloneSplit.chars, {
								duration: duration,
								delay: delay,
								rotationX: 0,
								stagger: stagger
							}, 0);
							return tl;
						};
						if ("all" === match_media_key) {
							initHeaders();
						} else {
							_gsap_mm.add("(".concat(match_media_key, ")"), function() {
								initHeaders();
							});
						}
					}
				},
				image_animation: function image_animation() {
					if (this.isEdit && !this.getElementSettings("wcf_img_animation_editor")) {
						return;
					}
					if ("reveal" === this.getElementSettings("wcf-image-animation")) {
						var wrap = this.findElement("img").parent();
						var element = this.$element;
						this.findElement("img").parent().parent().css("overflow", "hidden");
						wrap.css({
							overflow: "hidden",
							display: "block",
							visibility: "hidden",
							transition: "none"
						});
						var ease = this.getElementSettings("image-ease");
						var image_hover_effect = false;
						var image_hover_class = ["effect-zoom-in", "effect-zoom-out", "left-move", "right-move"];
						var image_hover_effect_class = "";
						$.each(image_hover_class, function(index, value) {
							if (element.hasClass("wcf--image-".concat(value))) {
								image_hover_effect = true;
								image_hover_effect_class = "wcf--image-".concat(value);
								element.removeClass(image_hover_effect_class);
							}
						});
						wrap.each(function() {
							var image = $(this).find("img");
							var tl = gsap.timeline({
								ScrollTrigger: {
									trigger: $(this),
									start: "top 50%"
								}
							});
							tl.set($(this), {
								autoAlpha: 1
							});
							tl.from($(this), 1.5, {
								xPercent: -100,
								ease: ease,
								onComplete: function onComplete() {
									if (image_hover_effect) {
										element.addClass(image_hover_effect_class);
										image_hover_effect = false;
									}
								}
							});
							tl.from(image, 1.5, {
								xPercent: 100,
								scale: 1.3,
								delay: -1.5,
								ease: ease
							});
						});
					}
					if ("scale" === this.getElementSettings("wcf-image-animation")) {
						var image = this.findElement("img");
						var start = this.getElementSettings("wcf-animation-start");
						if ("custom" === this.getElementSettings("wcf-animation-start")) {
							start = this.getElementSettings("wcf_animation_custom_start");
						}
						gsap.set(image, {
							scale: this.getElementSettings("wcf-scale-start")
						});
						gsap.to(image, {
							ScrollTrigger: {
								trigger: this.$element,
								start: start,
								scrub: true
							},
							scale: this.getElementSettings("wcf-scale-end"),
							ease: this.getElementSettings("image-ease")
						});
						image.parent().css("overflow", "hidden");
					}
					if ("stretch" === this.getElementSettings("wcf-image-animation")) {
						var _image = this.findElement("img");
						var _wrap = this.findElement("img").parent();
						_wrap.css("padding-bottom", "395px");
						var imageStretch = gsap.timeline({
							ScrollTrigger: {
								trigger: _wrap,
								start: "top top",
								pin: true,
								scrub: 1,
								pinSpacing: false,
								end: "bottom bottom+=100"
							}
						});
						imageStretch.to(_image, {
							width: "100%",
							borderRadius: "0px"
						});
						_wrap.css("transition", "none");
					}
				},
				fade_animation: function fade_animation() {
					var _this2 = this;
					if ("none" === this.getElementSettings("wcf-animation")) {
						return;
					}
					if (this.isEdit && !this.getElementSettings("wcf_enable_animation_editor")) {
						return;
					}
					var fade_direction = this.getElementSettings("fade-from");
					var onscroll_value = this.getElementSettings("on-scroll");
					var duration_value = this.getElementSettings("data-duration");
					var fade_offset = this.getElementSettings("fade-offset");
					var delay_value = this.getElementSettings("delay");
					var ease_value = this.getElementSettings("ease");
					var match_media_key = "all";
					this.$element.css("transition", "none");

					//if has min max key
					if (this.getElementSettings("fade_animation_breakpoint")) {
						var breakpoint = elementorBreakpoints[this.getElementSettings("fade_animation_breakpoint")].value;
						if ("min" === this.getElementSettings("fade_breakpoint_min_max")) {
							match_media_key = "min-width: " + breakpoint + "px";
						} else {
							match_media_key = "max-width: " + breakpoint + "px";
						}
					}
					var config = {
						opacity: 0,
						ease: ease_value,
						duration: duration_value,
						delay: delay_value
					};
					if ("fade" === this.getElementSettings("wcf-animation")) {
						if ("top" === fade_direction) {
							config.y = -fade_offset;
						}
						if ("bottom" === fade_direction) {
							config.y = fade_offset;
						}
						if ("left" === fade_direction) {
							config.x = -fade_offset;
						}
						if ("right" === fade_direction) {
							config.x = fade_offset;
						}
						if ("scale" === fade_direction) {
							config.scale = this.getElementSettings("wcf-a-scale");
						}
					}
					if ("move" === this.getElementSettings("wcf-animation")) {
						var rotation_di = this.getElementSettings("wcf_a_rotation_di");
						var transformOrigin = this.getElementSettings("wcf_a_transform_origin");
						var rotation = this.getElementSettings("wcf_a_rotation");
						config.force3D = true;
						config.transformOrigin = transformOrigin;
						if ("x" === rotation_di) {
							config.rotationX = rotation;
						}
						if ("y" === rotation_di) {
							config.rotationY = rotation;
						}
						gsap.set(this.$element.parent(), {
							perspective: 400
						});
					}
					if (onscroll_value) {
						config.ScrollTrigger = {
							trigger: this.$element,
							start: "top 85%"
						};
					}
					if ("all" === match_media_key) {
						gsap.from(this.$element, config);
					} else {
						_gsap_mm.add("(".concat(match_media_key, ")"), function() {
							gsap.from(_this2.$element, config);
							return function() {
								// optional
								// custom cleanup code here (runs when it STOPS matching)
							};
						});
					}
				},
				button_move_animation: function button_move_animation() {
					var btnWrap = this.findElement(".btn-wrapper");
					var btnCircle = this.findElement(".btn-item");
					if (btnWrap.length) {
						var callParallax = function callParallax(e) {
							parallaxIt(e, btnCircle, 80);
						};
						var parallaxIt = function parallaxIt(e, target, movement) {
							var relX = e.pageX - btnWrap.offset().left;
							var relY = e.pageY - btnWrap.offset().top;
							gsap.to(target, 0.5, {
								x: (relX - btnWrap.width() / 2) / btnWrap.width() * movement,
								y: (relY - btnWrap.height() / 2) / btnWrap.height() * movement,
								ease: Power2.easeOut
							});
						};
						btnWrap.mousemove(function(e) {
							callParallax(e);
						});
						btnWrap.mouseleave(function(e) {
							gsap.to(btnCircle, 0.5, {
								x: 0,
								y: 0,
								ease: Power2.easeOut
							});
						});
					}

					// Button Hover Animation
					var btn_hover_all = this.findElement(".btn-hover-bgchange");
					if (btn_hover_all.length) {
						var newSpan = document.createElement("span");
						btn_hover_all.append(newSpan);
						btn_hover_all.on("mouseenter", function(e) {
							var x = e.pageX - $(this).offset().left;
							var y = e.pageY - $(this).offset().top;
							$(this).find("span").css({
								top: y,
								left: x
							});
						});
						btn_hover_all.on("mouseout", function(e) {
							var x = e.pageX - $(this).offset().left;
							var y = e.pageY - $(this).offset().top;
							$(this).find("span").css({
								top: y,
								left: x
							});
						});
					}
				}
			});
			elementorFrontend.hooks.addAction("frontend/element_ready/widget", function($scope) {
				elementorFrontend.elementsHandler.addHandler(Animation, {
					$element: $scope
				});
			});
			elementorFrontend.hooks.addAction("frontend/element_ready/container", function($scope) {
				elementorFrontend.elementsHandler.addHandler(Animation, {
					$element: $scope
				});
			});

			// Pin Element
			var PingArea = Modules.extend({
				bindEvents: function bindEvents() {
					if (this.isEdit) {
						return;
					}
					if ("yes" !== this.getElementSettings("wcf_enable_pin_area")) {
						return;
					}
					if (this.getElementSettings("wcf_pin_breakpoint")) {
						if (device_width > elementorBreakpoints[this.getElementSettings("wcf_pin_breakpoint")].value) {
							this.run();
						}
					} else {
						this.run();
					}
				},
				run: function run() {
					var pin_area = this.$element;
					var pin_area_start = this.getElementSettings("wcf_pin_area_start");
					var pin_area_end = this.getElementSettings("wcf_pin_area_end");
					var end_trigger = this.getElementSettings("wcf_pin_end_trigger");
					var pin_status = this.getElementSettings("wcf_pin_status");
					var wcf_pin_spacing = this.getElementSettings("wcf_pin_spacing");
					var wcf_pin_type = this.getElementSettings("wcf_pin_type");
					var wcf_pin_scrub = this.getElementSettings("wcf_pin_scrub");
					var wcf_pin_markers = this.getElementSettings("wcf_pin_markers");
					if ("number" === wcf_pin_scrub) {
						wcf_pin_scrub = this.getElementSettings("wcf_pin_scrub_number");
					} else {
						wcf_pin_scrub = wcf_pin_scrub == "true" ? true : false;
					}
					if ("custom" === wcf_pin_spacing) {
						wcf_pin_spacing = this.getElementSettings("wcf_pin_spacing_custom");
					} else {
						wcf_pin_spacing = wcf_pin_spacing == "true" ? true : false;
					}
					if ("custom" === pin_status) {
						pin_status = this.getElementSettings("wcf_pin_custom");
					} else {
						pin_status = pin_status == "true" ? true : false;
					}
					if ("custom" === pin_area_start) {
						pin_area_start = this.getElementSettings("wcf_pin_area_start_custom");
					}
					if ("custom" === pin_area_end) {
						pin_area_end = this.getElementSettings("wcf_pin_area_end_custom");
					}
					if (this.getElementSettings("wcf_custom_pin_area")) {
						pin_area = this.getElementSettings("wcf_custom_pin_area");
					}
					gsap.to(this.$element, {
						ScrollTrigger: {
							trigger: pin_area,
							endTrigger: end_trigger,
							pin: pin_status,
							pinSpacing: wcf_pin_spacing,
							pinType: wcf_pin_type,
							start: pin_area_start,
							end: pin_area_end,
							scrub: wcf_pin_scrub,
							delay: 0.5,
							markers: wcf_pin_markers == "true" ? true : false
						}
					});
					this.$element.css("transition", "none");
				}
			});
			elementorFrontend.hooks.addAction("frontend/element_ready/container", function($scope) {
				elementorFrontend.elementsHandler.addHandler(PingArea, {
					$element: $scope
				});
			});
		}
		var wcf_popup = Modules.extend({
			bindEvents: function bindEvents() {
				this.run();
			},
			run: function run() {
				var _this3 = this;
				if (this.getElementSettings("wcf_enable_popup")) {
					if (this.isEdit && !this.getElementSettings("wcf_enable_popup_editor")) {}
					//open the popup
					this.$element.on("click", function(e) {
						e.preventDefault();
						if (_this3.isEdit && !_this3.getElementSettings("wcf_enable_popup_editor")) {
							return;
						}
						_this3.ajax_call();
					});
				}
			},
			ajax_call: function ajax_call() {
				var VideoAnimation = null;
				$.ajax({
					url: WCF_ADDONS_JS.ajaxUrl,
					data: {
						action: "wcf_load_popup_content",
						element_id: this.getID(),
						post_id: WCF_ADDONS_JS.post_id,
						nonce: WCF_ADDONS_JS._wpnonce
					},
					dataType: "json",
					type: "POST",
					success: function success(data) {
						if (!$("#wcf-aae-global--popup-js").find(".aae-popup-content-container").length) {
							$("body > #wcf-aae-global--popup-js").find(".aae-popup-content-container").html("<div class=\"aae-popup-content\"></div>");
						}
						$("#wcf-aae-global--popup-js").find(".aae-popup-content-container").html("".concat(data.html));
						VideoAnimation = gsap.timeline({
							defaults: {
								ease: "power2.inOut"
							}
						}).to("#wcf-aae-global--popup-js", {
							scaleY: 0.01,
							x: 1,
							opacity: 1,
							visibility: "visible",
							duration: 0.4
						}).to("#wcf-aae-global--popup-js", {
							scaleY: 1,
							duration: 0.6
						}).to("#wcf-aae-global--popup-js .wcf--popup-video", {
							scaleY: 1,
							opacity: 1,
							visibility: "visible",
							duration: 0.6
						}, "-=0.4");
					}
				});
				$(document).on("click", "#wcf-aae-global--popup-js .wcf--popup-close", function() {
					if (VideoAnimation) {
						VideoAnimation.timeScale(1.6).reverse();
					}
					VideoAnimation = null;
				});
			}
		});
		elementorFrontend.hooks.addAction("frontend/element_ready/container", function($scope) {
			elementorFrontend.elementsHandler.addHandler(wcf_popup, {
				$element: $scope
			});
		});
		var video_mask = function video_mask($scope) {
			$(".video--btn", $scope).on("click", function() {
				$scope.toggleClass("mask-open");
				$(".open-title", $scope).toggle();
				$(".close-title", $scope).toggle();
				var widget_id = $scope.data("id");
				$scope.closest(".wcf-video-mask-content").toggleClass("wcf-video-mask-content-".concat(widget_id));
				$("video", $scope).each(function() {
					if (this.autoplay) {
						return;
					}
					if (this.paused) {
						this.play();
					} else {
						this.pause();
					}
				});
			});
		};
		elementorFrontend.hooks.addAction("frontend/element_ready/wcf--video-mask.default", video_mask);
		var video_popup = function video_popup($currentEle) {
			var popup_content = $(".wcf--popup-video-wrapper").first();
			if (!popup_content.parent().is("body")) {
				if (!$("body > .wcf--popup-video-wrapper").length) {
					popup_content.appendTo("body");
				}
			}
			$currentEle.find(".wcf--popup-video-wrapper").remove();
			var open_popup = $currentEle.find(".wcf-popup-btn");
			// Open popup animation
			open_popup.off("click").on("click", function() {
				var $url = $(this).attr("data-src");
				$(".wcf--popup-video-wrapper").find(".aae-popup-content-container").html("");
				if (!popup_content.find("iframe").length) {
					$("body > .wcf--popup-video-wrapper").find(".aae-popup-content-container").html("<iframe src=\"".concat($url, "\" ></iframe>"));
				}
				window.VideoAnimation = gsap.timeline({
					defaults: {
						ease: "power2.inOut"
					}
				}).to(".wcf--popup-video-wrapper", {
					scaleY: 0.01,
					x: 1,
					opacity: 1,
					visibility: "visible",
					duration: 0.4
				}).to(".wcf--popup-video-wrapper", {
					scaleY: 1,
					duration: 0.6
				}).to(".wcf--popup-video-wrapper .wcf--popup-video", {
					scaleY: 1,
					opacity: 1,
					visibility: "visible",
					duration: 0.6
				}, "-=0.4");
			});
		};

		// Close popup animation (close button)
		$(document).on("click", ".wcf--popup-video-wrapper .wcf--popup-close", function() {
			close_video(VideoAnimation);
			window.VideoAnimation = null;
		});
		var close_video = function close_video(VideoAnimation) {
			if (VideoAnimation) {
				window.VideoAnimation.timeScale(1.6).reverse();
			}
		};

		// Video Box
		var video_box = function video_box($currentEle) {
			var video_box_video = $(".thumb video", $currentEle);
			if (video_box_video.length) {
				$(".wcf--video-box", $currentEle).hover(function() {
					video_box_video.get(0).play();
				}, function() {
					video_box_video.get(0).pause();
					video_box_video.get(0).currentTime = 0;
				});
			}
		};
		var video_widgets = ["video-box", "video-box-slider"];
		for (var _i = 0, _video_widgets = video_widgets; _i < _video_widgets.length; _i++) {
			var widget = _video_widgets[_i];
			elementorFrontend.hooks.addAction("frontend/element_ready/wcf--".concat(widget, ".default"), video_box);
		}
		elementorFrontend.hooks.addAction("frontend/element_ready/wcf--video-popup.default", video_popup);
		elementorFrontend.hooks.addAction("frontend/element_ready/wcf--video-box.default", video_popup);
		elementorFrontend.hooks.addAction("frontend/element_ready/wcf--video-box-slider.default", video_popup);
	});
})(jQuery);
//# sourceMappingURL=wcf-addons-ex copy.js.map
