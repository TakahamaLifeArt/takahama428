/*
 *	A controller event for the editing of the image by Design Creator
 */
// edit image
function clickme(my, id) {
	my.style.marginTop = '-96px';
	editimage(my, id);
}

function blurme(my) {
	my.style.marginTop = '-32px';
}

function overme(my) {
	my.style.marginTop = '-64px';
}


// move image 

// move center
function clickcenter(my, id) {
	my.style.marginTop = '-66px';
	moveimage(my, id);
}

function blurcenter(my) {
	my.style.marginTop = '-22px';
}

function overcenter(my) {
	my.style.marginTop = '-44px';
}

// move up right down left
function clickarrow(my, id) {
	my.style.marginTop = '-72px';
	moveimage(my, id);
}

function blurarrow(my) {
	my.style.marginTop = '-24px';
}

function overarrow(my) {
	my.style.marginTop = '-48px';
}

// move edge vertical
function clickvert(my, id) {
	my.style.top = '-18px';
	moveimage(my, id);
}

function blurvert(my) {
	my.style.top = '-6px';
}

function oververt(my) {
	my.style.top = '-12px';
}

// move edge horizontal
function clickhor(my, id) {
	my.style.top = '-72px';
	moveimage(my, id);
}

function blurhor(my) {
	my.style.top = '-24px';
}

function overhor(my) {
	my.style.top = '-48px';
}
