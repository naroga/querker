Frequently Asked Questions
==========================

Why are you using a custom PriorityQueue implementation? Why not \SplPriorityQueue?
-----------------------------------------------------------------------------------

`\SplPriorityQueue`, although very neat and optimized, is not serializable. Querker relies heavily
on serialization for persistently storing the queue with its processes.