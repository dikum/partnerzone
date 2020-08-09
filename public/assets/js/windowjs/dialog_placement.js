	function resetDialogZIndexes(){
		for(var index in dialogIndexArray){
			console.log('Resetting ' + index);
			$('#'+index).css('zIndex', '9999');
		}
	}

	function bringToFront(dialogId){
		console.log('Bringing to front ' + dialogId);
		$('#'+dialogId).css('zIndex', '99999');
	}

	function minimzeAllWindows(){
		for(var index in windowObjectArray){
			windowObjectArray[index].changeWindowState(WindowState.MINIMIZED);
		}
	}