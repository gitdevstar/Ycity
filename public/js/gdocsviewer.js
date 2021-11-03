/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/gdocsviewer.js":
/*!*************************************!*\
  !*** ./resources/js/gdocsviewer.js ***!
  \*************************************/
/***/ (() => {

eval("/*\n * jQuery.gdocViewer - Embed linked documents using Google Docs Viewer\n * Licensed under MIT license.\n * Date: 2011/01/16\n *\n * @author Jawish Hameed\n * @version 1.0\n */\n(function ($) {\n  $.fn.gdocsViewer = function (options) {\n    var settings = {\n      width: '600',\n      height: '700'\n    };\n\n    if (options) {\n      $.extend(settings, options);\n    }\n\n    return this.each(function () {\n      var file = $(this).attr('href');\n      var ext = file.substring(file.lastIndexOf('.') + 1);\n\n      if (/^(tiff|pdf|ppt|pps|doc|docx)$/.test(ext)) {\n        $(this).after(function () {\n          var id = $(this).attr('id');\n          var gdvId = typeof id !== 'undefined' && id !== false ? id + '-gdocsviewer' : '';\n          return '<div id=\"' + gdvId + '\" class=\"gdocsviewer\"><iframe src=\"http://docs.google.com/viewer?embedded=true&url=' + encodeURIComponent(file) + '\" width=\"' + settings.width + '\" height=\"' + settings.height + '\" style=\"border: none;\"></iframe></div>';\n        });\n      }\n    });\n  };\n})(jQuery);//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvZ2RvY3N2aWV3ZXIuanM/OTdkMiJdLCJuYW1lcyI6WyIkIiwiZm4iLCJnZG9jc1ZpZXdlciIsIm9wdGlvbnMiLCJzZXR0aW5ncyIsIndpZHRoIiwiaGVpZ2h0IiwiZXh0ZW5kIiwiZWFjaCIsImZpbGUiLCJhdHRyIiwiZXh0Iiwic3Vic3RyaW5nIiwibGFzdEluZGV4T2YiLCJ0ZXN0IiwiYWZ0ZXIiLCJpZCIsImdkdklkIiwiZW5jb2RlVVJJQ29tcG9uZW50IiwialF1ZXJ5Il0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsQ0FBQyxVQUFTQSxDQUFULEVBQVc7QUFDUkEsRUFBQUEsQ0FBQyxDQUFDQyxFQUFGLENBQUtDLFdBQUwsR0FBbUIsVUFBU0MsT0FBVCxFQUFrQjtBQUVqQyxRQUFJQyxRQUFRLEdBQUc7QUFDWEMsTUFBQUEsS0FBSyxFQUFJLEtBREU7QUFFWEMsTUFBQUEsTUFBTSxFQUFHO0FBRkUsS0FBZjs7QUFLQSxRQUFJSCxPQUFKLEVBQWE7QUFDVEgsTUFBQUEsQ0FBQyxDQUFDTyxNQUFGLENBQVNILFFBQVQsRUFBbUJELE9BQW5CO0FBQ0g7O0FBRUQsV0FBTyxLQUFLSyxJQUFMLENBQVUsWUFBVztBQUN4QixVQUFJQyxJQUFJLEdBQUdULENBQUMsQ0FBQyxJQUFELENBQUQsQ0FBUVUsSUFBUixDQUFhLE1BQWIsQ0FBWDtBQUNBLFVBQUlDLEdBQUcsR0FBR0YsSUFBSSxDQUFDRyxTQUFMLENBQWVILElBQUksQ0FBQ0ksV0FBTCxDQUFpQixHQUFqQixJQUF3QixDQUF2QyxDQUFWOztBQUVBLFVBQUksZ0NBQWdDQyxJQUFoQyxDQUFxQ0gsR0FBckMsQ0FBSixFQUErQztBQUMzQ1gsUUFBQUEsQ0FBQyxDQUFDLElBQUQsQ0FBRCxDQUFRZSxLQUFSLENBQWMsWUFBWTtBQUN0QixjQUFJQyxFQUFFLEdBQUdoQixDQUFDLENBQUMsSUFBRCxDQUFELENBQVFVLElBQVIsQ0FBYSxJQUFiLENBQVQ7QUFDQSxjQUFJTyxLQUFLLEdBQUksT0FBT0QsRUFBUCxLQUFjLFdBQWQsSUFBNkJBLEVBQUUsS0FBSyxLQUFyQyxHQUE4Q0EsRUFBRSxHQUFHLGNBQW5ELEdBQW9FLEVBQWhGO0FBQ0EsaUJBQU8sY0FBY0MsS0FBZCxHQUFzQixxRkFBdEIsR0FBOEdDLGtCQUFrQixDQUFDVCxJQUFELENBQWhJLEdBQXlJLFdBQXpJLEdBQXVKTCxRQUFRLENBQUNDLEtBQWhLLEdBQXdLLFlBQXhLLEdBQXVMRCxRQUFRLENBQUNFLE1BQWhNLEdBQXlNLHlDQUFoTjtBQUNILFNBSkQ7QUFLSDtBQUNKLEtBWE0sQ0FBUDtBQVlILEdBdkJEO0FBd0JILENBekJELEVBeUJJYSxNQXpCSiIsInNvdXJjZXNDb250ZW50IjpbIi8qXG4gKiBqUXVlcnkuZ2RvY1ZpZXdlciAtIEVtYmVkIGxpbmtlZCBkb2N1bWVudHMgdXNpbmcgR29vZ2xlIERvY3MgVmlld2VyXG4gKiBMaWNlbnNlZCB1bmRlciBNSVQgbGljZW5zZS5cbiAqIERhdGU6IDIwMTEvMDEvMTZcbiAqXG4gKiBAYXV0aG9yIEphd2lzaCBIYW1lZWRcbiAqIEB2ZXJzaW9uIDEuMFxuICovXG4oZnVuY3Rpb24oJCl7XG4gICAgJC5mbi5nZG9jc1ZpZXdlciA9IGZ1bmN0aW9uKG9wdGlvbnMpIHtcblxuICAgICAgICB2YXIgc2V0dGluZ3MgPSB7XG4gICAgICAgICAgICB3aWR0aCAgOiAnNjAwJyxcbiAgICAgICAgICAgIGhlaWdodCA6ICc3MDAnXG4gICAgICAgIH07XG5cbiAgICAgICAgaWYgKG9wdGlvbnMpIHtcbiAgICAgICAgICAgICQuZXh0ZW5kKHNldHRpbmdzLCBvcHRpb25zKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiB0aGlzLmVhY2goZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICB2YXIgZmlsZSA9ICQodGhpcykuYXR0cignaHJlZicpO1xuICAgICAgICAgICAgdmFyIGV4dCA9IGZpbGUuc3Vic3RyaW5nKGZpbGUubGFzdEluZGV4T2YoJy4nKSArIDEpO1xuXG4gICAgICAgICAgICBpZiAoL14odGlmZnxwZGZ8cHB0fHBwc3xkb2N8ZG9jeCkkLy50ZXN0KGV4dCkpIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmFmdGVyKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGlkID0gJCh0aGlzKS5hdHRyKCdpZCcpO1xuICAgICAgICAgICAgICAgICAgICB2YXIgZ2R2SWQgPSAodHlwZW9mIGlkICE9PSAndW5kZWZpbmVkJyAmJiBpZCAhPT0gZmFsc2UpID8gaWQgKyAnLWdkb2Nzdmlld2VyJyA6ICcnO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gJzxkaXYgaWQ9XCInICsgZ2R2SWQgKyAnXCIgY2xhc3M9XCJnZG9jc3ZpZXdlclwiPjxpZnJhbWUgc3JjPVwiaHR0cDovL2RvY3MuZ29vZ2xlLmNvbS92aWV3ZXI/ZW1iZWRkZWQ9dHJ1ZSZ1cmw9JyArIGVuY29kZVVSSUNvbXBvbmVudChmaWxlKSArICdcIiB3aWR0aD1cIicgKyBzZXR0aW5ncy53aWR0aCArICdcIiBoZWlnaHQ9XCInICsgc2V0dGluZ3MuaGVpZ2h0ICsgJ1wiIHN0eWxlPVwiYm9yZGVyOiBub25lO1wiPjwvaWZyYW1lPjwvZGl2Pic7XG4gICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcbn0pKCBqUXVlcnkgKTtcbiJdLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvZ2RvY3N2aWV3ZXIuanMuanMiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/gdocsviewer.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/gdocsviewer.js"]();
/******/ 	
/******/ })()
;