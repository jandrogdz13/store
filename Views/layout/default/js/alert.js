const Alert_Js = {
	time_interval: false,

	auto_close: function(args = {}){
		const self = this;
		//let timerInterval;
		return Swal.fire({
			title: typeof args.title !== 'undefined'? args.title: '',
			text: typeof args.text !== 'undefined'? args.text: '',
			timer: typeof args.timer !== 'undefined'? args.timer: 2000,
			timerProgressBar: typeof args.progress_bar !== 'undefined'? args.progress_bar: true,
			icon: typeof args.icon !== 'undefined'? args.icon: 'info',
			showConfirmButton: typeof args.confirm !== 'undefined'? args.confirm: false,
			willClose: () => {
				clearInterval(self.time_interval);
			}
		});
	},
};
