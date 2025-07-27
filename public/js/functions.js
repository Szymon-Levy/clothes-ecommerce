/**
 * Limits function execution to given time interval
 */

const throttleFunction = (callback, interval = 100) => {
  let isRunning = false
  
  return (...args) => {
    if (!isRunning) {
      isRunning = true

      callback.apply(this, args)

      setTimeout(() => {
        isRunning = false
      }, interval);
    }
  }
}