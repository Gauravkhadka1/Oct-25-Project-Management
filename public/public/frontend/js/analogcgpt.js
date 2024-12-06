const clock = document.querySelector('.clock');
const pointer = document.querySelector('.pointer');

// Add event listener to track mouse movement within the clock
clock.addEventListener('mousemove', (event) => {
  const clockRect = clock.getBoundingClientRect();
  const mouseX = event.clientX - clockRect.left;
  const mouseY = event.clientY - clockRect.top;
  const centerX = clockRect.width / 2;
  const centerY = clockRect.height / 2;
  const deltaX = mouseX - centerX;
  const deltaY = mouseY - centerY;
  const angle = Math.atan2(deltaY, deltaX) * (180 / Math.PI) + 90;
  pointer.style.transform = `translate(-50%, -100%) rotate(${angle}deg)`;
});
